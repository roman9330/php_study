<?php

use Interfaces\IUrlDecoder;
use Interfaces\IUrlEncoder;

include dirname(__DIR__) . "/Interfaces/IUrlDecoder.php";
include dirname(__DIR__) . "/Interfaces/IUrlEncoder.php";

class ShortUrl implements IUrlEncoder, IUrlDecoder

{
    protected static $chars = "123456789bcdfghjkmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ";
    protected static $table = "short_urls";
    protected static $checkUrlExists = true;

    protected $pdo;
    protected $timestamp;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->timestamp = $_SERVER["REQUEST_TIME"];
    }

    public function urlToShortCode($url): string
    {

        if (empty($url)) {
            throw new \Exception("Не получен адрес URL.");
        }

        if (self::$checkUrlExists) {
            if (!$this->verifyUrlExists($url)) {
                throw new \Exception(
                    "Адрес URL не существует.");
            }
        }

        $shortCode = $this->urlExistsInDb($url);
        if ($shortCode == false) {
            $shortCode = $this->createShortCode($url);
        }

        return $shortCode;
    }

    protected function verifyUrlExists($url)
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

    protected function urlExistsInDb($url)
    {
        $query = "SELECT short_code FROM " . self::$table .
            " WHERE long_url = :long_url LIMIT 1";
        $stmt = $this->pdo->prepare($query);
        $params = array(
            "long_url" => $url
        );
        $stmt->execute($params);

        $result = $stmt->fetch();
        return (empty($result)) ? false : $result["short_code"];
    }

    protected function createShortCode($url)
    {
        $id = $this->insertUrlInDb($url);
        $shortCode = $this->convertIntToShortCode($id);
        $this->insertShortCodeInDb($id, $shortCode);
        return $shortCode;
    }

    protected function insertUrlInDb($url)
    {
        $query = "INSERT INTO " . self::$table .
            " (long_url, date_created) " .
            " VALUES (:long_url, :timestamp)";
        $stmnt = $this->pdo->prepare($query);
        $params = array(
            "long_url" => $url,
            "timestamp" => $this->timestamp
        );
        $stmnt->execute($params);

        return $this->pdo->lastInsertId();
    }

    protected function convertIntToShortCode($id)
    {
        $id = intval($id);
        if ($id < 1) {
            throw new \Exception(
                "ID не является некорректным целым числом.");
        }

        $length = strlen(self::$chars);
        // Проверяем, что длина строки
        // больше минимума - она должна быть
        // больше 10 символов
        if ($length < 10) {
            throw new \Exception("Длина строки мала");
        }

        $code = "";
        while ($id > $length - 1) {
            // Определяем значение следующего символа
            // в коде и подготавливаем его
            $code = self::$chars[fmod($id, $length)] .
                $code;
            // Сбрасываем $id до оставшегося значения для конвертации
            $id = floor($id / $length);
        }

        // Оставшееся значение $id меньше, чем
        // длина self::$chars
        $code = self::$chars[$id] . $code;

        return $code;
    }

    protected function insertShortCodeInDb($id, $code)
    {
        if ($id == null || $code == null) {
            throw new \Exception("Параметры ввода неправильные.");
        }
        $query = "UPDATE " . self::$table .
            " SET short_code = :short_code WHERE id = :id";
        $stmnt = $this->pdo->prepare($query);
        $params = array(
            "short_code" => $code,
            "id" => $id
        );
        $stmnt->execute($params);

        if ($stmnt->rowCount() < 1) {
            throw new \Exception(
                "Строка не обновляется коротким кодом.");
        }

        return true;
    }

    public function shortCodeToUrl($code, $increment = true)
    {
        if (empty($code)) {
            throw new \Exception("Не задан короткий код.");
        }

         $urlRow = $this->getUrlFromDb($code);
        if (empty($urlRow)) {
            throw new \Exception(
                "Короткий код не содержится в базе.");
        }

        if ($increment == true) {
            $this->incrementCounter($urlRow["id"]);
        }

        return $urlRow["long_url"];
    }


    protected function getUrlFromDb($code)
    {
        $query = "SELECT id, long_url FROM " . self::$table .
            " WHERE short_code = :short_code LIMIT 1";
        $stmt = $this->pdo->prepare($query);
        $params = array(
            "short_code" => $this->getShortLinkId($code)
        );
        $stmt->execute($params);

        $result = $stmt->fetch();
        return (empty($result)) ? false : $result;
    }

    private function getShortLinkId ($code)
    {
        return str_replace(SHORTURL_PREFIX, '', $code);
    }

    protected function incrementCounter($id)
    {
        $query = "UPDATE " . self::$table .
            " SET counter = counter + 1 WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $params = array(
            "id" => $id
        );
        $stmt->execute($params);
    }

    /**
     * @throws Exception
     */
    public function decode(string $code): string
    {
        return $this->shortCodeToUrl($code);
    }

    /**
     * @throws Exception
     */
    public function encode(string $url): string
    {
        return $this->urlToShortCode($url);
    }
}
