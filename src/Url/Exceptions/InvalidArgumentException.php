<?php

namespace MyStudy\Url\Exceptions;

use Exception;

class InvalidArgumentException extends Exception
{
    protected $message = 'Ошибка данных';
}