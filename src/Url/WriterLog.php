<?php

namespace MyStudy\Url;

use Monolog\{
    Handler\StreamHandler,
    Level,
    Logger
};

require_once "Include/config.php";

class WriterLog
{
    private static ?WriterLog $instance = null;
    private Logger $logger;

    private function __construct()
    {
        $this->logger = new Logger('general');
        $this->logger->pushHandler(new StreamHandler(LOG_PATH, Level::Info));
    }

    public static function getInstance(): ?WriterLog
    {
        if (!self::$instance) {
            self::$instance = new WriterLog;
        }
        return self::$instance;
    }

    public function write($msgType, $message): void
    {
        $this->logger->log($msgType, $message);
    }
}