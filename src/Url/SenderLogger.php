<?php

namespace MyStudy\Url;

use Monolog\{
    Level,
    Logger
};


class SenderLogger
{
    private Logger $logger;

    public function __construct(string $message, Level $msgType)
    {
        $this->logger = new Logger('general');
        $handler = StHandler::getInstance(__DIR__ . '/../Log.log', Level::Info)->getHandler();
        //$handler = $instance->getHandler();
        //$this->logger->pushHandler(new StreamHandler(__DIR__ . '/../Log.log', Level::Info));
        $this->logger->pushHandler($handler);
        $this->logger->log($msgType, $message);
    }
}