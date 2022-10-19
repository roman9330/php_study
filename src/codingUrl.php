<?php

use MyStudy\Url\{
    DataRepository,
    UrlCoder,
    SenderLogger
};

require_once "Url/Include/config.php";
//require_once 'autoload.php';
require_once 'vendor/autoload.php';

try {
    $pdo = new PDO(DB_PDODRIVER . ":host=" . DB_HOST .
        ";dbname=" . DB_DATABASE_NAME,
        DB_USERNAME, DB_PASSWORD);
    $coder = new UrlCoder(new DataRepository($pdo));
} catch (PDOException $e) {
    $e->getMessage();
    $log = new SenderLogger('Ошибка соединения с базой данных', \Monolog\Level::Error);
    exit;
}

$quetion = readline("Выберите операцию: (1 - кодировать / 2 - декодировать)");
switch ($quetion) {
    case 1:
        $longUrl = readline("Введите URL: ");
        $result = "Короткая ссылка: " . $coder->encode($longUrl);
        if(!$result){
            $result = 'Во время выполнения произошли ошибки! Подробности смотрите в логе';
        }
        break;
    case 2:
        $shortCode = readline("Введите короткую ссылку: ");
        $result = "Длинная ссылка: " . $coder->decode($shortCode);
        break;
    default:
        $result = "Чего?";
}

echo $result . PHP_EOL;
