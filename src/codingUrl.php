<?php
/**
 * При запуске программы массив 'db' (свойство класса DataRepository) заполняется
 * записями из базы данных. Во время выполнения программы все действия происходят с этим массивом.
 *  При завершении программы, новые данные из массива записываются в базу данных.
 */

use MyStudy\Url\{
    DataRepository,
    UrlCoder,
    SenderLogger
};
use Monolog\Level;

require_once "Url/Include/config.php";
//require_once 'autoload.php';
require_once 'vendor/autoload.php';

try {
    $pdo = new PDO(DB_PDODRIVER . ":host=" . DB_HOST .
        ";dbname=" . DB_DATABASE_NAME,
        DB_USERNAME, DB_PASSWORD);
    $coder = new UrlCoder(new DataRepository($pdo));
} catch (PDOException $e) {
    new SenderLogger($e->getMessage(), Level::Error);
    echo ("Во время выполнения программы произошли ошибки!. Подробности смотрите в логе") . PHP_EOL;
    exit;
}

programStart($coder);

function programStart(UrlCoder $coder): void
{
    $question = readline("Выберите операцию: (1 - кодировать / 2 - декодировать)");
    switch ($question) {
        case 1:
            $longUrl = readline("Введите URL: ");
            $result = $coder->encode($longUrl);
            if (mb_strlen($result) > 0) {
                $result = "Короткая ссылка: " . $result;
            } else {
                $result = 'Во время выполнения произошли ошибки! Подробности смотрите в логе';
            }
            break;
        case 2:
            $shortCode = readline("Введите короткую ссылку: ");
            $result = $coder->decode($shortCode);
            if (mb_strlen($result) > 0) {
                $result = "Длинная ссылка: " . $result;
            } else {
                $result = 'Во время выполнения произошли ошибки! Подробности смотрите в логе';
            }
            break;
        default:
            $result = "Чего?";
    }
    echo $result . PHP_EOL;
    continueProgram($coder);
}

function continueProgram(UrlCoder $coder): void
{
    $question = readline("Хотоие продолжить?: (1 - да)");
    switch ($question) {
        case 1:
            programStart($coder);
            break;
        default:
            exit();
    }
}