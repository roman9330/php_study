<?php
namespace MyStudy\Url;

use Monolog\Handler\StreamHandler;
use Monolog\Level;


class StHandler {
    private static $instance = null;
    private string $path; # = __DIR__ . '/../Log.log';
    private Level $level; # = Level::Info;
    private StreamHandler $sHandler;

    private function __construct($path, $level)
    {
        $this->path = $path;
        $this->level = $level;
        $this->sHandler = new StreamHandler($this->path, $this->level);
    }

    public static function getInstance($path, $level)
    {
        if(!self::$instance)
        {
            self::$instance = new StHandler($path, $level);
        }
        return self::$instance;
    }

    public function getHandler()
    {
        return $this->sHandler;
    }
}



//$instance = StHandler::getInstance();
//$conn = $instance->getHandler();