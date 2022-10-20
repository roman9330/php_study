<?php
namespace MyStudy\Url;
use Monolog\Level;

class Validator
{
    public function validateUrl(string $url): bool
    {
        if(empty($url) || !filter_var($url, FILTER_VALIDATE_URL) || !$this->checkUrl($url)){
            new SenderLogger('Неверный Url ' . $url, Level::Alert);
            return false;
        }
        return true;
    }
    
    protected function checkUrl(string $url): bool
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        $response = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return (!empty($response) && $response != 404);
    }
}