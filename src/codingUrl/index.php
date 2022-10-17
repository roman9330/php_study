<?php
include __DIR__ . "/Include/config.php";
include __DIR__ . "/Classes/shortUrl.php";
include __DIR__ . "/Actions/encode.php";
include __DIR__ . "/Actions/decode.php";

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
        $result = encode($pdo);
//        $longUrl = readline("Введите URL: ");
//        $shortUrl = new ShortUrl($pdo);
//        $code = $shortUrl->encode($longUrl);
//        $result = "Короткая ссылка: " . SHORTURL_PREFIX . $code;
        break;
    case 2:
        $result = decode($pdo);
//        $shortcode = readline("Введите короткую ссылку: ");
//        $shortUrl = new ShortUrl($pdo);
//        $result = "Длинная ссылка: " . $shortUrl->decode($shortcode);
        break;
    default:
        $result = "Чего?";
}

echo $result;
