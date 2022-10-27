<?php

namespace Doctor\PhpPro\Core\CLI;

use Doctor\PhpPro\Core\Interfaces\ISingleton;
use Doctor\PhpPro\Core\Interfaces\IWriter;
use Doctor\PhpPro\Core\Traits\SingletonTrait;
use UfoCms\ColoredCli\CliColor;

class CLIWriter implements IWriter, ISingleton
{
    use SingletonTrait;

    protected CliColor $color;

    public function setColor(CliColor $color): self
    {
        $this->color = $color;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function writeBorder(int $length = self::BORDER_LENGTH): self
    {
        return $this->writeLn(str_repeat('*', $length));
    }

    /**
     * @inheritDoc
     */
    public function writeLn(string $msg): self
    {
        return $this->write($msg, true);
    }

    /**
     * @inheritDoc
     */
    public function write(string $msg, bool $endLine = false): self
    {
        echo $this->color->value . $msg . ($endLine ? PHP_EOL : '') . CliColor::RESET->value;
        return $this;
    }
}

