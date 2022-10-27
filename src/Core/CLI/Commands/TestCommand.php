<?php

namespace Doctor\PhpPro\Core\CLI\Commands;

use UfoCms\ColoredCli\CliColor;

class TestCommand extends AbstractCommand
{
    public function run(array $params = []): void
    {
        parent::run($params);
        $this->writer->setColor(CliColor::CYAN)->writeLn('Hello World');
    }

    public static function getCommandDesc(): string
    {
        return 'This command demonstrates a simple use of the CLI';
    }
}
