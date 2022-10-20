<?php

namespace MyStudy\Url;

use Monolog\{
    Handler\StreamHandler,
    Level,
    Logger
};

class SenderLogger
{
    private Logger $logger;

    public function __construct(string $message, Level $msgType)
    {
        $this->logger = new Logger('general');
        $this->logger->pushHandler(new StreamHandler(__DIR__ . '/../Log.log', Level::Info));
        $this->logger->log($msgType, $message);
    }
}