<?php

namespace MyStudy\Url\Exceptions;

use Exception;

class NotFoundException extends Exception
{
    protected $message = 'Data not found';
}