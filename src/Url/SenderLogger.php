<?php

namespace MyStudy\Url;

use Monolog\Level;
use Monolog\Logger;

class SenderLogger
{
    private $logger;

    public function __construct(string $message, Level $level)
    {
        $this->logger = new Logger('general');
        $this->logger->pushHandler(new \Monolog\Handler\StreamHandler(__DIR__ . '/../Log.log', $level));
        $this->logger->alert($message);
    }
}