<?php

namespace Doctor\PhpPro\Core\CLI\Commands;

use Doctor\PhpPro\Shortener\UrlConverter;
use UfoCms\ColoredCli\CliColor;

class UrlDecodeCommand extends AbstractCommand
{
    protected UrlConverter $convertor;

    /**
     * @param UrlConverter $convertor
     */
    public function __construct(UrlConverter $convertor)
    {
        parent::__construct();
        $this->convertor = $convertor;
    }

    public function run(array $params = []): void
    {
        parent::run($params);
        $this->writer->setColor(CliColor::CYAN)
            ->writeLn('Shortcode: ' . $this->convertor->decode($params[0]));
    }

    public static function getCommandDesc(): string
    {
        return 'Decode shortcode to url';
    }
}
