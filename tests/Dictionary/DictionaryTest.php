<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Dictionary;

use PHPUnit\Framework\TestCase;

/**
 * Коды сверяются с классификаторами стандарта 2.2.2:
 * https://github.com/1C-Company/DirectBank/blob/2.2.2/doc/common-section/tables.md
 */
final class DictionaryTest extends TestCase
{
    protected static function values(string $class): array
    {
        return array_filter((new \ReflectionClass($class))->getConstants(), fn($value) => ! is_array($value));
    }

    public function testDocKind()
    {
        $codes = array_values(array_unique(self::values(DocKind::class)));
        sort($codes);

        $this->assertSame([
            '01', '02', '03', '04', '05', '06',
            '10', '11', '12', '13', '14', '15', '16', '17', '18',
            '19', '20', '21', '22', '23', '24', '25',
            '30', '35',
        ], $codes);

        $this->assertSame(['02', '03', '05', '06'], DocKind::REQUIRED);
        $this->assertSame(DocKind::STATUS_PACKET_NOTICE, DocKind::SHIPPING_CONTAINER_HANDLING_STATUS_NOTIFICATION);
    }

    public function testDocStatus()
    {
        $this->assertSame(['01', '02', '03', '04', '05', '06'], array_values(self::values(DocStatus::class)));
        $this->assertSame(['01'], array_values(self::values(PacketStatus::class)));
    }

    public function testStatementType()
    {
        $this->assertSame([0, 1, 2], array_values(self::values(StatementType::class)));
    }

    public function testErrorCode()
    {
        $codes = array_values(self::values(ErrorCode::class));

        $this->assertSame(array_unique($codes), $codes);
        $this->assertSame(
            array_merge(
                array_map(fn($i) => (string) (1000 + $i), range(1, 13)),
                array_map(fn($i) => (string) (1100 + $i), range(1, 6)),
                array_map(fn($i) => (string) (1200 + $i), range(1, 5)),
                array_map(fn($i) => (string) (2000 + $i), range(1, 15)),
                array_map(fn($i) => (string) (2100 + $i), range(1, 2)),
                array_map(fn($i) => (string) (2200 + $i), range(1, 7)),
            ),
            $codes
        );
        $this->assertSame(['1006', '1007'], ErrorCode::REAUTHENTICATE);
    }
}
