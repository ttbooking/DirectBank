<?php
declare(strict_types=1);

namespace TTBooking\DirectBank\Objects;


/**
 * Аутентификация по логину и паролю (Settings/Data/Logon/Login)
 */
class SettingsLoginType
{
    //Логин пользователя
    protected string $User;

    public function getUser(): string
    {
        return $this->User;
    }
}
