<?php

namespace MyStudy\Url;

use MyStudy\Url\Interfaces\{IUrlDecoder, IUrlEncoder};
use MyStudy\Url\Exceptions\NotFoundException;
use MyStudy\Url\Validator;

/**
 *
 */
class UrlCoder implements IUrlEncoder, IUrlDecoder
{
    protected static $chars = "123456789bcdfghjkmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ";
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
        return $this->repository->getUrlByCode($code);
    }

    /**
     * @param string $url
     * @return string
     */
    public function encode(string $url): string
    {
        $valid = new Validator;
        $isValid = $valid->validateUrl($url);
        if ($isValid) {
            $code = $this->repository->getCodeByUrl($url);
            if (!$code) {
                $code = $this->generateAndLoadCode($url);
            }
            return $code;
        }
        return false;
    }

    /**
     * @param string $url
     * @return string
     */
    public function generateAndLoadCode(string $url): string
    {
        $code = $this->generateCode();
        if (!$this->repository->codeIsset($code)) {
            $this->repository->loadCode($code, $url);
        } else {
            $this->generateAndLoadCode($url);
        }
        return $code;
    }

    /**
     * @return string
     */
    private function generateCode(): string
    {
        return substr(str_shuffle($this::$chars), 0, 10);
    }

}