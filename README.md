# DirectBank

PHP-клиент для обмена с банком по протоколу **1С:DirectBank**, стандарт `2.3.2` (поддерживается и `2.2.2`).

Библиотека берёт на себя HTTP-транспорт (аутентификация, сессия, заголовки протокола)
и даёт типизированные объекты для транспортного контейнера (`Packet`) и документов
внутри него: запрос выписки, выписка, извещение о состоянии обработки контейнера и др.

[![Tests](https://github.com/ttbooking/DirectBank/actions/workflows/tests.yml/badge.svg)](https://github.com/ttbooking/DirectBank/actions/workflows/tests.yml)
[![Packagist](https://img.shields.io/packagist/v/ttbooking/direct-bank.svg)](https://packagist.org/packages/ttbooking/direct-bank)
[![License: GPL v3](https://img.shields.io/badge/License-GPLv3-blue.svg)](LICENSE)

## Требования

- PHP 8.0+
- [`guzzlehttp/guzzle`](https://github.com/guzzle/guzzle) ^7.0 | ^8.0
- [`ttbooking/mapper-php`](https://packagist.org/packages/ttbooking/mapper-php) ^2.3 — маппинг объектов в XML и обратно
- [`ramsey/uuid`](https://github.com/ramsey/uuid) ^4.0
- `psr/log` ^1 | ^2 | ^3 (опционально, для логирования запросов)

## Установка

```bash
composer require ttbooking/direct-bank
```

## Быстрый старт

### Создание клиента

```php
use TTBooking\DirectBank\Client;
use TTBooking\DirectBank\FormatVersion;

$client = new Client([
    'url'        => 'https://bank.example.ru/API/v1/directbank/', // базовый URL сервиса банка
    'customerId' => '40702810000000000000',                      // идентификатор клиента в банке
    'login'      => 'user',
    'password'   => 'secret',
    'apiVersion' => null,                                         // по умолчанию FormatVersion::getDefault(), '2.3.2'
    'availableApiVersion' => FormatVersion::LATEST,               // заголовок AvailableAPIVersion, null — не передавать
    'userAgent'  => null,                                         // заголовок User-Agent, по умолчанию стандартный Guzzle
    'sessionId'  => null,                                         // можно передать уже полученный SID
    'verify'     => true,                                         // проверка SSL-сертификата
    'handler'    => null,                                         // свой Guzzle handler, например MockHandler в тестах
]);
```

Настройки проверяются в конструкторе: `url`, `customerId`, `login`, `password` и `apiVersion`
должны быть непустыми строками, `availableApiVersion`, `userAgent` и `sessionId` — непустой строкой
или `null`, `verify` — булевым значением или путём к CA-бандлу, `handler` — callable. Иначе выбрасывается
`TTBooking\DirectBank\Exceptions\InvalidSettingsException` (наследник `\InvalidArgumentException`)
с именем неверной настройки.

Вторым аргументом можно передать любой PSR-3 логгер — все HTTP-запросы и ответы
будут записаны в формате `MessageFormatter::DEBUG` из Guzzle:

```php
$client = new Client($settings, $logger); // Psr\Log\LoggerInterface
```

### Версия стандарта

По умолчанию клиент и все создаваемые документы используют версию `2.3.2`. Для банка,
который поддерживает только `2.2.x`, версия задаётся один раз:

```php
use TTBooking\DirectBank\FormatVersion;

FormatVersion::setDefault('2.2.2'); // APIVersion клиента и formatVersion новых документов
```

Разобранные документы сохраняют версию из XML. Элементы 2.3.x — письма, `SenderFootprint`,
`Letters` в настройках — в схемах `2.2.2` отсутствуют.

Транспортный контейнер отправляется с UTF-8 BOM, как требует стандарт с версии 2.3.x.

### Сессия

Явно вызывать `createSession()` не обязательно: при первом запросе, требующем
авторизации, клиент сам выполнит `Logon` и подставит полученный `sid` в заголовки.
Если сессия истекла или стала недействительной (ошибки банка `1006` и `1007`),
клиент войдёт заново и повторит запрос один раз.

```php
$sid = $client->createSession();
```

Логин и пароль передаются только в `Logon`, остальные запросы идут с `SID`.

### Вход с одноразовым паролем

Если банк требует подтвердить вход одноразовым паролем (OTP), `createSession()` — и неявный
вход перед первым запросом — выбрасывает `TTBooking\DirectBank\Exceptions\OtpRequiredException`.
Банк в этот момент отправляет пароль клиенту, а вход подтверждается методом `confirmOtp()`:

```php
use TTBooking\DirectBank\Exceptions\OtpRequiredException;

try {
    $client->createSession();
} catch (OtpRequiredException $e) {
    $e->getPhoneMask();   // маска телефона, если банк её прислал, например '7916***6465'
    $e->getSessionCode(); // короткий код сессии для показа пользователю, если есть

    $client->confirmOtp($e->getSessionId(), $otpFromUser); // дальше запросы идут с авторизованным SID
}
```

### Методы клиента

| Метод | Запрос DirectBank | Результат |
|---|---|---|
| `createSession(): string` | `POST Logon` | идентификатор сессии (SID) |
| `confirmOtp(string $sessionId, string $otp): string` | `POST LogonOTP` | идентификатор авторизованной сессии |
| `sendPack(Packet $packet): string` | `POST SendPack` | идентификатор принятого контейнера |
| `getPackList(?DateTimeInterface $date = null): ?array` | `GET GetPackList` | список идентификаторов контейнеров, готовых к получению |
| `getPackListResponse(DateTimeInterface\|string\|null $since = null)` | `GET GetPackList` | список контейнеров и отметка времени последнего из них |
| `getPack(string $id): Packet` | `GET GetPack` | транспортный контейнер |
| `getSettings(string $inn, string $bic, ?string $account = null): Settings` | `POST GetSettings` | настройки обмена с банком |

Отметка времени для списка контейнеров задаётся по часам сервера банка и передаётся
в формате `dd.MM.yyyy HH:mm:ss`. Чтобы получать только новые контейнеры, передавайте
в следующий запрос `TimeStampLastPacket` из предыдущего ответа:

```php
$list = $client->getPackListResponse($lastTimestamp); // null — все контейнеры

foreach ($list->getPacketID() as $id) {
    // $client->getPack($id) ...
}

$lastTimestamp = $list->getTimeStampLastPacket() ?? $lastTimestamp; // сохранить до следующего запроса
```

### Настройки обмена

Настройки обмена с банком можно получить автоматически. Если идентификатор клиента в банке ещё
неизвестен, в `customerId` передаётся `'0'`; из настроек его можно взять для дальнейшей работы:

```php
$settings = (new Client(['customerId' => '0'] + $credentials))
    ->getSettings(inn: '7705260699', bic: '044525888', account: '40702810813123123222');

$data = $settings->getData();
$data->getCustomerID();        // идентификатор клиента в банке
$data->getBankServerAddress(); // адрес ресурса банка
$data->getDocKinds();          // виды документов, которыми возможен обмен: ['03', '05', '10', ...]
$data->getLogon()->getLogin()?->getUser();
```

### Ошибки

- `TTBooking\DirectBank\Exceptions\ClientException` — банк вернул ошибку (`ResultBank/Error`),
  при любом HTTP-статусе. Код банка как есть — `getBankCode()` (строка, например `'1201'`),
  вся ошибка — `getError()`, описание — `getMessage()`, `getCode()` — код числом.
- `TTBooking\DirectBank\Exceptions\UnexpectedResponseException` (наследник `ClientException`) —
  ответ не удалось разобрать или в нём нет ожидаемых данных. HTTP-ответ — `getResponse()`,
  `getCode()` — HTTP-статус.
- `TTBooking\DirectBank\Exceptions\OtpRequiredException` (наследник `ClientException`) —
  банк требует подтвердить вход одноразовым паролем, см. выше.
- Коды ошибок банка — константы `TTBooking\DirectBank\Dictionary\ErrorCode`.
- Сетевые ошибки (нет соединения, таймаут) пробрасываются как исключения Guzzle.

## Примеры

### Запрос выписки

Документ внутри контейнера передаётся в base64 в `Document/Data`,
а его вид указывается кодом из `DocKind`.

```php
use Ramsey\Uuid\Uuid;
use TTBooking\DirectBank\Dictionary\DocKind;
use TTBooking\DirectBank\Objects\{
    BankPartyType, BankType, CustomerPartyType, DocumentType,
    Packet, ParticipantType, StatementRequest, StatementRequestData
};

$now       = new DateTimeImmutable();
$userAgent = 'My App';
$docId     = (string) Uuid::uuid4();

$customer = (new CustomerPartyType())->setId('40702810000000000000');
$bank     = (new BankPartyType())->setBic('044525593');

$request = (new StatementRequest())
    ->setId($docId)
    ->setCreationDate($now->format(DATE_ATOM))
    ->setUserAgent($userAgent)
    ->setSender($customer)
    ->setRecipient($bank)
    ->setData(
        (new StatementRequestData())
            ->setStatementType(0)
            ->setDateFrom('2021-01-01T00:00:00+03:00')
            ->setDateTo('2021-01-31T23:59:59+03:00')
            ->setAccount('40702810000000000000')
            ->setBank((new BankType())->setBic('044525593'))
    );

$packet = (new Packet())
    ->setId((string) Uuid::uuid4())
    ->setCreationDate($now->format(DATE_ATOM))
    ->setUserAgent($userAgent)
    ->setSender((new ParticipantType())->setCustomer($customer))
    ->setRecipient((new ParticipantType())->setBank($bank))
    ->setDocument(
        (new DocumentType())
            ->setId($docId)
            ->setDockind(DocKind::BANK_STATEMENT_REQUEST)
            ->setData(base64_encode((string) $request))
    );

$packetId = $client->sendPack($packet);
```

### Платёжное поручение

```php
use Ramsey\Uuid\Uuid;
use TTBooking\DirectBank\Dictionary\DocKind;
use TTBooking\DirectBank\Objects\{
    BankPartyType, BankType, CustomerDetailsType, CustomerPartyType, DocumentType,
    Packet, ParticipantType, PayDocRu, PayDocRuApp
};

$customer = (new CustomerPartyType())->setId('2806')->setInn('7705260699');
$bank     = (new BankPartyType())->setBic('044525888');

$payDocRu = (new PayDocRu())
    ->setId((string) Uuid::uuid4())
    ->setCreationDate((new DateTimeImmutable())->format(DATE_ATOM))
    ->setSender($customer)
    ->setRecipient($bank)
    ->setData(
        (new PayDocRuApp())
            ->setDocNo('14')
            ->setDocDate('2026-09-22')
            ->setSum(1234.56)
            ->setPayer(
                (new CustomerDetailsType())
                    ->setName('ООО "Ромашка"')->setINN('7705260699')->setKPP('770501001')
                    ->setAccount('40702810813123123222')
                    ->setBank((new BankType())->setBic('044525888'))
            )
            ->setPayee(
                (new CustomerDetailsType())
                    ->setName('ООО "Лютик"')->setINN('7704596181')
                    ->setAccount('40702810401200000035')
                    ->setBank((new BankType())->setBic('044525999'))
            )
            ->setTransitionKind('01')
            ->setPriority('5')
            ->setPurpose('Оплата по счёту № 15')
        // ->setBudgetPaymentInfo(...) — реквизиты бюджетного платежа
    );
// ->setDigest(new DigestType($digest, $algorithmVersion)) — если банк требует дайджест

$packet = (new Packet())
    ->setId((string) Uuid::uuid4())
    ->setCreationDate((new DateTimeImmutable())->format(DATE_ATOM))
    ->setSender((new ParticipantType())->setCustomer($customer))
    ->setRecipient((new ParticipantType())->setBank($bank))
    ->setDocument(
        (new DocumentType())
            ->setId($payDocRu->getId())
            ->setDockind(DocKind::PAY_DOC_RU)
            ->setData(base64_encode((string) $payDocRu))
    );

$client->sendPack($packet);
```

Платёжное требование собирается так же: `PayRequest` с данными `PayRequestApp` и видом `DocKind::PAY_REQUEST`.
Исходящие документы собираются в XML через `TTBooking\DirectBank\Mapper\XmlMapper`: поля базовых
типов идут раньше полей наследников, как требует XSD.

### Письмо

Письмо (вид `40`) ходит в обе стороны. Размер и CRC32 вложений считаются по содержимому файла:

```php
use TTBooking\DirectBank\Objects\{BinaryFileType, Letter, LetterAttachmentType, LetterDataType, LinkedDocType};

$letter = (new Letter())
    ->setId((string) Uuid::uuid4())
    ->setCreationDate((new DateTimeImmutable())->format(DATE_ATOM))
    ->setSender((new ParticipantType())->setCustomer($customer))
    ->setRecipient((new ParticipantType())->setBank($bank))
    ->setData(
        (new LetterDataType())
            ->setDocNum('7')
            ->setDocDate('2026-09-23')
            ->setLetterTypeCode('01')      // типы писем банка — в настройках: Settings::getData()->getLetters()
            ->setTheme('Уточнение назначения платежа')
            ->setText('Прошу уточнить назначение платежа по поручению № 14.')
            ->addAttachment(new LetterAttachmentType(BinaryFileType::fromFile('/path/to/invoice.pdf')))
            ->setLinkedDoc(new LinkedDocType($payDocRu->getId(), DocKind::PAY_DOC_RU))
    );
```

Вложения передаются как есть: BOM для текстовых файлов добавляет вызывающий код. У полученного
письма содержимое вложения — `getBinaryFile()->getContents()`, проверка размера и CRC32 — `isIntact()`.

### Получение ответов банка

```php
use Mapper\XmlModelMapper;
use TTBooking\DirectBank\Dictionary\DocKind;
use TTBooking\DirectBank\Dictionary\DocStatus;
use TTBooking\DirectBank\Objects\{Letter, Settings, Statement, StatusDocNotice, StatusPacketNotice};

$mapper = new XmlModelMapper();

foreach ($client->getPackList() ?? [] as $id) {
    $pack = $client->getPack($id);

    foreach ($pack->getDocuments() as $packDocument) {
        $xml = base64_decode($packDocument->getData());

        $document = match ($packDocument->getDockind()) {
            DocKind::STATUS_PACKET_NOTICE => $mapper->map($xml, new StatusPacketNotice()),
            DocKind::STATUS_DOC_NOTICE => $mapper->map($xml, new StatusDocNotice()),
            DocKind::SETTINGS => $mapper->map($xml, new Settings()),
            DocKind::BANK_STATEMENT => $mapper->map($xml, new Statement()),
            DocKind::LETTER => $mapper->map($xml, new Letter()),
            default => null,
        };

        if ($document instanceof StatusDocNotice) {
            $document->getExtID();                              // документ, о котором извещение
            $document->getResult()->getStatus()?->getCode();    // DocStatus::EXECUTED и т.д.
            $document->getResult()->getError()?->getDescription(); // или ошибка обработки
        }

        if ($document instanceof Statement) {
            $data = $document->getData();
            $data->getClosingBalance();
            foreach ($data->getOperationInfo() as $operation) {
                // ...
            }
        }
    }
}
```

### Служебные документы

Исходящие служебные документы собираются так же, как запрос выписки, и передаются
в контейнере с соответствующим видом:

| Документ | Класс | Вид ЭД |
|---|---|---|
| Запрос о состоянии электронного документа | `StatusRequest` (`setExtID()` — ИД документа) | `DocKind::STATUS_REQUEST` |
| Запрос об отзыве электронного документа | `CancelationRequest` (`setExtID()`, `setReason()`) | `DocKind::CANCELATION_REQUEST` |
| Запрос-зонд | `Probe` | `DocKind::PROBE` |

Все исходящие документы принимают необязательный дайджест: `setDigest(new DigestType($data, $algorithmVersion))`.
Как его формировать, стандарт не описывает — это делает внешняя компонента банка.

Входящие: `StatusPacketNotice` (`01`), `StatusDocNotice` (`02`), `Settings` (`06`), `Statement` (`15`), `Letter` (`40`).

IP- и MAC-адреса клиента передаются в контейнере: `$packet->setSenderFootprint(new SenderFootprintType(['192.168.1.10'], ['00-1A-2B-3C-4D-5E']))`.

## Справочники

Классификаторы стандарта в пространстве имён `TTBooking\DirectBank\Dictionary`:

| Класс | Содержимое |
|---|---|
| `DocKind` | коды видов электронных документов, `DocKind::REQUIRED` — обязательные |
| `DocStatus` | коды статусов электронных документов |
| `PacketStatus` | коды статусов транспортных контейнеров |
| `StatementType` | типы выписок |
| `ErrorCode` | коды ошибок банковского сервиса |

## Виды документов

Константы `TTBooking\DirectBank\Dictionary\DocKind`:

| Константа | Код | Документ |
|---|---|---|
| `STATUS_PACKET_NOTICE` | `01` | Извещение о состоянии обработки транспортного контейнера |
| `STATUS_DOC_NOTICE` | `02` | Извещение о состоянии электронного документа * |
| `STATUS_REQUEST` | `03` | Запрос о состоянии электронного документа * |
| `CANCELATION_REQUEST` | `04` | Запрос об отзыве электронного документа |
| `PROBE` | `05` | Запрос-зонд * |
| `SETTINGS` | `06` | Настройки обмена с банком * |
| `PAY_DOC_RU` | `10` | Платёжное поручение |
| `PAY_REQUEST` | `11` | Платёжное требование |
| `COLLECTION_ORDER` | `12` | Инкассовое поручение |
| `INNER_DOC` | `13` | Внутренний банковский документ |
| `BANK_STATEMENT_REQUEST` | `14` | Запрос выписки |
| `BANK_STATEMENT` | `15` | Выписка банка |
| `MEM_ORDER` | `16` | Мемориальный ордер |
| `PAYMENT_ORDER` | `17` | Платёжный ордер |
| `BANK_ORDER` | `18` | Банковский ордер |
| `WAGES_*` | `19`–`23` | Документы зарплатного проекта |
| `CASH_CONTRIBUTION` | `24` | Объявление на взнос наличными |
| `CHECK` | `25` | Денежный чек |
| `CURRENCY_TRANSFER_ORDER` | `30` | Поручение на перевод валюты |
| `CURRENCY_STATEMENT` | `35` | Выписка по валютному счёту |
| `LETTER` | `40` | Письмо |

\* обязательные по стандарту. `SHIPPING_CONTAINER_HANDLING_STATUS_NOTIFICATION` — прежнее имя `STATUS_PACKET_NOTICE`.

XSD-схемы формата лежат в [`tests/Fixture/xsd`](tests/Fixture/xsd) (версия 2.3.2), схемы 2.2.2 — в [`tests/Fixture/xsd/2.2.2`](tests/Fixture/xsd/2.2.2).

## Тесты

```bash
composer install
vendor/bin/phpunit
```

По умолчанию запускаются офлайн-тесты: маппинг XML на примерах из описания стандарта 1С
и работа `Client` с подменённым HTTP-обработчиком. Тесты против тестового стенда банка
(`tests/ClientTest.php`) требуют сетевого доступа к нему и запускаются отдельно:

```bash
vendor/bin/phpunit --group bank-stand
```

## История изменений

См. [Releases](https://github.com/ttbooking/DirectBank/releases).

## Лицензия

[GPL-3.0](LICENSE)
