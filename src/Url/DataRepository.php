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
            WriterLog::getInstance()->write(Level::Info, "Данные успешно прочитаны");
            //new SenderLogger("Данные успешно прочитаны", Level::Info);
        } catch (NotConnectException $e) {
            WriterLog::getInstance()->write(Level::Error, $e->getMessage());
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
            WriterLog::getInstance()->write(Level::Info, "Данные успешно записаны");
        } catch (NotConnectException $e) {
            WriterLog::getInstance()->write(Level::Error, $e->getMessage());
        }
    }

    public function codeIsset(string $code): bool
    {
        $result = false;
        foreach ($this->db as $item) {
            if ($item['short_code'] == $code) {
                $result = true;
            }
        }
        return $result;
    }

    public function getUrlByCode(string $code): string|bool
    {
        $result = false;
        foreach ($this->db as $item) {
            if ($item['short_code'] == $code) {
                $result = $item['long_url'];
            }
        }
        if(!$result) {
            WriterLog::getInstance()->write(Level::Alert, "Несуществующий код " . $code);
        }
        return $result;
    }

    public function getCodeByUrl(string $url): string|bool
    {
        $result = false;
        foreach ($this->db as $item) {
            if ($item['long_url'] == $url) {
                $result = $item['short_code'];
            }
        }
        return $result;
    }

    public function addingCodeToArray(string $code, string $url): void
    {
        $newRow['long_url'] = $url;
        $newRow['short_code'] = $code;
        $newRow['isNew'] = 1;
        $this->db[] = $newRow;
        WriterLog::getInstance()->write(Level::Info, "Добавлен новый код для Url " . $url);
    }
}
