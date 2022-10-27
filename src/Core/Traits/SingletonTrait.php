<?php

namespace Doctor\PhpPro\Core\Traits;

use LogicException;

trait SingletonTrait
{
    protected static ?self $instance = null;

    public static function getInstance(): self
    {
        if (null === self::$instance) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    protected function __construct()
    {
    }

    protected function __clone()
    {
    }

    /**
     * @throws LogicException
     */
    public function __wakeup()
    {
        $this->accessDenied(__METHOD__);
    }

    /**
     * @throws LogicException
     */
    public function __unserialize($array)
    {
        $this->accessDenied(__METHOD__);
    }

    protected function accessDenied($method)
    {
        throw new LogicException('Cannot call method: ' . $method . ' a singleton.');
    }
}