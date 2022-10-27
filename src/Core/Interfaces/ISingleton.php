<?php

namespace Doctor\PhpPro\Core\Interfaces;

interface ISingleton
{
    public static function getInstance(): self;
}