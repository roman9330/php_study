<?php

namespace Doctor\PhpPro\Core\CLI\Helpers;

class CliParamAnalyzer
{
    protected static bool $initStatus = false;
    protected static string $command = '';
    protected static array $arguments = [];

    /**
     * @return void
     */
    protected static function init(): void
    {
        if (false === static::$initStatus){
            global $argv;
            $params = $argv;
            array_splice($params, 0, 1);
            if (false === empty($params)) {
                static::$command = current($params);
                array_splice($params, 0, 1);
            }
            static::$arguments = $params;
            static::$initStatus = true;
        }
    }

    /**
     * @return string
     */
    public static function getCommand(): string
    {
        static::init();
        return self::$command;
    }

    /**
     * @return array
     */
    public static function getArguments(): array
    {
        static::init();
        return self::$arguments;
    }

    /**
     * @return bool
     */
    public static function isVerbose(): bool
    {
        return static::checkParameter('-v')
            || static::checkParameter('--verbose');
    }

    /**
     * @return bool
     */
    public static function isInteractive(): bool
    {
        return static::checkParameter('-i')
            || static::checkParameter('--interactive');
    }

    /**
     * @param string $param
     * @return bool
     */
    public static function checkParameter(string $param): bool
    {
        static::init();
        return in_array($param, static::$arguments);
    }
    
}
