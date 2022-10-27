<?php

namespace Doctor\PhpPro\Core\CLI\Commands;

use Doctor\PhpPro\Shortener\UrlConverter;
use UfoCms\ColoredCli\CliColor;

class UrlEncodeCommand extends AbstractCommand
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

    /**
     * @inheritDoc
     */
    public function run(array $params = []): void
    {
        parent::run($params);
        $this->writer->setColor(CliColor::CYAN)
            ->writeLn('Shortcode: ' . $this->convertor->encode($params[0]));
    }

    /**
     * @inheritDoc
     */
    public static function getCommandDesc(): string
    {
        return 'Encode the url to sort code';
    }

}
