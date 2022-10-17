<?php

function encode($pdo):string
{
    $longUrl = readline("Введите URL: ");
    $shortUrl = new ShortUrl($pdo);
    $code = $shortUrl->encode($longUrl);
    return "Короткая ссылка: " . SHORTURL_PREFIX . $code;
}
