<?php

namespace Doctor\PhpPro\Core\CLI\Commands;

use Doctor\PhpPro\Core\CLI\CLIWriter;
use Doctor\PhpPro\Core\CLI\Helpers\CliParamAnalyzer;
use Doctor\PhpPro\Core\CLI\Interfaces\ICliCommand;
use Doctor\PhpPro\Core\Interfaces\IWriter;
use UfoCms\ColoredCli\CliColor;

abstract class AbstractCommand implements ICliCommand
{
    const NAME = 'undefined';

    protected array $params = [];

    protected IWriter $writer;

    public function __construct()
    {
        $this->writer = CLIWriter::getInstance();
    }

    /**
     * @inheritDoc
     */
    public static function getCommandName(): string
    {
        $name = static::NAME;
        if (static::NAME === self::NAME) {
            $path = explode('\\', static::class);
            $classCommandName = str_replace('Command', '', array_pop($path));
            $name = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $classCommandName));
        }
        return $name;
    }

    /**
     * @inheritDoc
     */
    public static function getCommandDesc(): string
    {
        return '';
    }

    protected function printHeader(): void
    {
        $this->writer->setColor(CliColor::YELLOW)->writeBorder()
            ->writeLn(static::getCommandName())
            ->writeLn(static::getCommandDesc())
            ->writeBorder();
    }

    public function run(array $params = []): void
    {
        $this->params = $params;
        if (CliParamAnalyzer::isVerbose()) {
            $this->printHeader();
        }
    }
}
