# DirectBank

PHP-клиент для обмена с банком по протоколу **1С:DirectBank** (формат обмена `2.2.2`).

Библиотека берёт на себя HTTP-транспорт (аутентификация, сессия, заголовки протокола)
и даёт типизированные объекты для транспортного контейнера (`Packet`) и документов
внутри него: запрос выписки, выписка, извещение о состоянии обработки контейнера и др.

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
use TTBooking\DirectBank\Dictionary\DefaultValue;

$client = new Client([
    'url'        => 'https://bank.example.ru/API/v1/directbank/', // базовый URL сервиса банка
    'customerId' => '40702810000000000000',                      // идентификатор клиента в банке
    'login'      => 'user',
    'password'   => 'secret',
    'apiVersion' => DefaultValue::FORMAT_VERSION,                 // по умолчанию '2.2.2'
    'sessionId'  => null,                                         // можно передать уже полученный SID
    'verify'     => true,                                         // проверка SSL-сертификата
]);
```

Настройки проверяются в конструкторе: `url`, `customerId`, `login`, `password` и `apiVersion`
должны быть непустыми строками, `sessionId` — строкой или `null`, `verify` — булевым значением
или путём к CA-бандлу. Иначе выбрасывается
`TTBooking\DirectBank\Exceptions\InvalidSettingsException` (наследник `\InvalidArgumentException`)
с именем неверной настройки.

Вторым аргументом можно передать любой PSR-3 логгер — все HTTP-запросы и ответы
будут записаны в формате `MessageFormatter::DEBUG` из Guzzle:

```php
$client = new Client($settings, $logger); // Psr\Log\LoggerInterface
```

### Сессия

Явно вызывать `createSession()` не обязательно: при первом запросе, требующем
авторизации, клиент сам выполнит `Logon` и подставит полученный `sid` в заголовки.

```php
$sid = $client->createSession();
```

### Методы клиента

| Метод | Запрос DirectBank | Результат |
|---|---|---|
| `createSession(): string` | `POST Logon` | идентификатор сессии (SID) |
| `sendPack(Packet $packet): string` | `POST SendPack` | идентификатор принятого контейнера |
| `getPackList(?DateTimeInterface $date = null): ?array` | `GET GetPackList` | список идентификаторов контейнеров, готовых к получению |
| `getPack(string $id): Packet` | `GET GetPack` | транспортный контейнер |

Если банк вернул ошибку (`ResultBank/Error`), выбрасывается
`TTBooking\DirectBank\Exceptions\ClientException` с кодом и описанием из ответа.
Транспортные ошибки пробрасываются как исключения Guzzle.

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

### Получение ответов банка

```php
use Mapper\XmlModelMapper;
use TTBooking\DirectBank\Dictionary\DocKind;
use TTBooking\DirectBank\Objects\{Statement, StatusPacketNotice};

$mapper = new XmlModelMapper();

foreach ($client->getPackList() ?? [] as $id) {
    $pack = $client->getPack($id);
    $xml  = base64_decode($pack->getDocument()->getData());

    $document = match ($pack->getDocument()->getDockind()) {
        DocKind::SHIPPING_CONTAINER_HANDLING_STATUS_NOTIFICATION => $mapper->map($xml, new StatusPacketNotice()),
        DocKind::BANK_STATEMENT => $mapper->map($xml, new Statement()),
        default => null,
    };

    if ($document instanceof Statement) {
        $data = $document->getData();
        $data->getClosingBalance();
        foreach ($data->getOperationInfo() as $operation) {
            // ...
        }
    }
}
```

## Виды документов

Константы `TTBooking\DirectBank\Dictionary\DocKind`:

| Константа | Код | Документ |
|---|---|---|
| `SHIPPING_CONTAINER_HANDLING_STATUS_NOTIFICATION` | `01` | Извещение о состоянии обработки транспортного контейнера |
| `PAY_DOC_RU` | `10` | Платёжное поручение |
| `PAY_REQUEST` | `11` | Платёжное требование |
| `COLLECTION_ORDER` | `12` | Инкассовое поручение |
| `INNER_DOC` | `13` | Внутренний банковский документ |
| `BANK_STATEMENT_REQUEST` | `14` | Запрос выписки |
| `BANK_STATEMENT` | `15` | Выписка банка |
| `MEM_ORDER` | `16` | Мемориальный ордер |
| `PAYMENT_ORDER` | `17` | Платёжный ордер |
| `BANK_ORDER` | `18` | Банковский ордер |
| `CASH_CONTRIBUTION` | `24` | Объявление на взнос наличными |
| `CHECK` | `25` | Денежный чек |

XSD-схемы формата лежат в [`tests/Fixture/xsd`](tests/Fixture/xsd).

## Тесты

```bash
composer install
vendor/bin/phpunit
```

`tests/Objects` — офлайн-тесты маппинга XML. `tests/ClientTest.php` обращается
к тестовому стенду банка и требует сетевого доступа к нему.

## История изменений

См. [Releases](https://github.com/ttbooking/DirectBank/releases).

## Лицензия

[GPL-3.0](LICENSE)
