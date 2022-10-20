<?php

namespace MyStudy\Url;

use Carbon\Carbon;
use Monolog\Level;
use MyStudy\Url\Exceptions\{NotConnectException};
use PDO;


class DataRepository
{
    protected object $pdo;
    protected array $db = [];
    protected static string $table = "short_urls";
    protected int $timestamp;

    /**
     * @param object $pdo
     */
    public function __construct(object $pdo)
    {
        $this->pdo = $pdo;
        $this->getDbFromDatabase();
    }

    protected function getDbFromDatabase(): void
    {
        try {
            $query = "SELECT short_code, long_url, 0 as isNew FROM " . self::$table;
            $recordset = $this->pdo->prepare($query);
            $recordset->execute();
            $this->db = $recordset->fetchAll(PDO::FETCH_ASSOC);
            new SenderLogger("Данные успешно прочитаны", Level::Info);
        } catch (NotConnectException $e) {
            new SenderLogger($e->getMessage(), Level::Error);
        }
    }

    public function __destruct()
    {
        try {
            $this->timestamp = Carbon::now()->timestamp;
            foreach ($this->db as $dbRow) {
                if ($dbRow['isNew'] !== 0) {
                    $query = "INSERT INTO " . self::$table .
                        " (long_url, short_code, date_created) " .
                        " VALUES (:longurl, :shortcode, :timestamp)";
                    $recordset = $this->pdo->prepare($query);
                    $params = array(
                        "longurl" => $dbRow['long_url'],
                        "shortcode" => $dbRow['short_code'],
                        "timestamp" => $this->timestamp
                    );
                    $recordset->execute($params);
                }
            }
            new SenderLogger("Данные успешно записаны", Level::Info);
        } catch (NotConnectException $e) {
            new SenderLogger($e->getMessage(), Level::Error);
        }
    }

    public function codeIsset(string $code): bool
    {
        foreach ($this->db as $item) {
            if ($item['short_code'] == $code) {
                return true;
            }
        }
        return false;
    }

    public function getUrlByCode(string $code): string|bool
    {
        foreach ($this->db as $item) {
            if ($item['short_code'] == $code) {
                return $item['long_url'];
            }
        }
        new SenderLogger("Несуществующий код " . $code, Level::Alert);
        return false;
    }

    public function getCodeByUrl(string $url): string|bool
    {
        foreach ($this->db as $item) {
            if ($item['long_url'] == $url) {
                return $item['short_code'];
            }
        }
        return false;
    }

    public function addingCodeToArray(string $code, string $url): void
    {
        $newRow['long_url'] = $url;
        $newRow['short_code'] = $code;
        $newRow['isNew'] = 1;
        $this->db[] = $newRow;
        new SenderLogger("Добавлен новый код для Url " . $url, Level::Alert);
    }
}
