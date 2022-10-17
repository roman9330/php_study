<?php
include __DIR__ . "/include/config.php";
include __DIR__ . "/include/shortUrl.php";

try {
    $pdo = new PDO(DB_PDODRIVER . ":host=" . DB_HOST .
        ";dbname=" . DB_DATABASE_NAME,
        DB_USERNAME, DB_PASSWORD);
} catch (\PDOException $e) {
    trigger_error("Ошибка: не могу установить соединение с базой данных.");
    exit;
}

$quetion = readline("Выберите операцию: (1 - кодировать / 2 - декодировать)");
switch ($quetion) {
    case 1:
        $longUrl = readline("Введите URL: ");
        $shortUrl = new ShortUrl($pdo);
        $code = $shortUrl->encode($longUrl);
        $result = "Короткая ссылка: " . SHORTURL_PREFIX . $code;
        break;
    case 2:
        $shortcode = readline("Введите короткую ссылку: ");
        $shortUrl = new ShortUrl($pdo);
        $result = "Длинная ссылка: " . $shortUrl->decode($shortcode);
        break;
    default:
        $result = "Чего?";
}

echo $result . PHP_EOL;


