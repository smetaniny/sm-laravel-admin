<?php

namespace Smetaniny\SmLaravelAdmin\Exceptions;

use Exception;

class SmAdminException extends Exception
{
    // Дополнительное свойство для хранения кода ошибки
    protected int $errorCode;

    // Метод для получения кода ошибки
    public function getErrorCode(): int
    {
        return $this->errorCode;
    }

    // Конструктор с добавлением кода ошибки
    public function __construct($message = "", $code = 0, Exception $previous = null, $errorCode = 0)
    {
        parent::__construct($message, $code, $previous);
        $this->errorCode = $errorCode;
    }
}
