<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Dictionary;


/**
 * Типы выписок банка
 *
 * @see https://github.com/1C-Company/DirectBank/blob/2.3.2/doc/common-section/tables.md#statementType
 */
class StatementType
{
    //Окончательная выписка
    const FINAL = 0;
    //Промежуточная выписка
    const INTERIM = 1;
    //Текущий остаток на счете
    const CURRENT_BALANCE = 2;
}
