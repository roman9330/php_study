<?php


function decode($pdo): string
{
    $shortcode = readline("Введите короткую ссылку: ");
    $shortUrl = new ShortUrl($pdo);
    return "Длинная ссылка: " . $shortUrl->decode($shortcode);
}
