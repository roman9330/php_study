<?php

namespace MyStudy\Url;

use GuzzleHttp\Client;
use MyStudy\Url\Interfaces\{
    IUrlDecoder,
    IUrlEncoder
};

require_once "Include/config.php";

/**
 *
 */
class UrlCoder implements IUrlEncoder, IUrlDecoder
{
    protected static string $chars = "123456789bcdfghjkmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ";
    protected DataRepository $repository;

    public function __construct($rep)
    {
        $this->repository = $rep;
    }

    /**
     * @param string $code
     * @return string
     */
    public function decode(string $code): string
    {
        $url = $this->repository->getUrlByCode($code);
        if (!$url) {
            $url = '';
        }
        return $url;
    }

    /**
     * @param string $url
     * @return string
     */
    public function encode(string $url): string
    {
        $valid = new Validator(new Client());
        $isValid = $valid->validateUrl($url);
        if ($isValid) {
            $code = $this->repository->getCodeByUrl($url);
            if (!$code) {
                $code = $this->generateAndAddingCode($url);
            }
        }else{
            $code = '';
        }
        return $code;
    }

    /**
     * @param string $url
     * @return string
     */
    public function generateAndAddingCode(string $url): string
    {
        $code = $this->generateCode();
        if (!$this->repository->codeIsset($code)) {
            $this->repository->addingCodeToArray($code, $url);
        } else {
            $this->generateAndAddingCode($url);
        }
        return $code;
    }

    /**
     * @return string
     */
    private function generateCode(): string
    {
        return substr(str_shuffle($this::$chars), 0, CODE_LENGTH);
    }

}