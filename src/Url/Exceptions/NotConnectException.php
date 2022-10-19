<?php

namespace MyStudy\Url\Exceptions;

use Exception;


class NotConnectException extends Exception
{
    protected $message = 'Connection ERROR!!!';
}