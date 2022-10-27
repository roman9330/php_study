<?php

namespace Doctor\PhpPro\Core\Interfaces;


interface IWriter
{
    const BORDER_LENGTH = 50;

    /**
     * @param string $msg
     * @param bool $endLine
     * @return IWriter
     */
    public function write(string $msg, bool $endLine = false): self;

    /**
     * @param string $msg
     * @return IWriter
     */
    public function writeLn(string $msg): self;

    /**
     * @param int $length
     * @return IWriter
     */
    public function writeBorder(int $length = self::BORDER_LENGTH): self;
}

