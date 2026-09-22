<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


/**
 * Параметры получения выписки в автоматическом режиме (Settings/Data/ReceiptStatement)
 */
class ReceiptStatementType
{
    //Логин, по которому можно получать только выписку банка
    protected string $Login;

    //Инструкция по получению пароля для этого логина
    protected string $Instructions;

    public function getLogin(): string
    {
        return $this->Login;
    }

    public function getInstructions(): string
    {
        return $this->Instructions;
    }
}
