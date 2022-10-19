<?php

namespace MyStudy\Url;

use MyStudy\Url\Exceptions\{NotConnectException, NotFoundException};
use PDO;

class DataRepository
{
    protected object $pdo;
    protected array $db = [];
    protected static string $table = "short_urls";
    protected $timestamp;

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
        $query = "SELECT short_code, long_url, 0 as isNew FROM " . self::$table;
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $this->db = $stmt->fetchAll(PDO::FETCH_CLASS);
    }

    public function __destruct()
    {
        try {
            $this->timestamp = $_SERVER["REQUEST_TIME"];
            foreach ($this->db as $dbrow) {
                if ($dbrow->isNew !== 0) {
                    $query = "INSERT INTO " . self::$table .
                        " (long_url, short_code, date_created) " .
                        " VALUES (:longurl, :shortcode, :timestamp)";
                    $stmnt = $this->pdo->prepare($query);
                    $params = array(
                        "longurl" => $dbrow['long_url'],
                        "shortcode" => $dbrow['short_code'],
                        "timestamp" => $this->timestamp
                    );
                    $stmnt->execute($params);
                    return true;
                }
            }
        } catch (NotConnectException $e) {
            $e->getMessage();
        }
    }

    public function codeIsset(string $code): bool
    {
        foreach ($this->db as $item) {
            if ($item->short_code == $code) {
                return true;
            }
        }
        return false;
    }

    public function getUrlByCode(string $code): string|bool
    {
        foreach ($this->db as $item) {
            if ($item->short_code == $code) {
                return $item->long_url;
            }
        }
        return false;
    }

    public function getCodeByUrl(string $url): string|bool
    {
        foreach ($this->db as $item) {
            if ($item->long_url == $url) {
                return $item->short_code;
            }
        }
        return false;
    }

    public function loadCode(string $code, string $url)
    {
        $newRow['long_url'] = $url;
        $newRow['short_code'] = $code;
        $newRow['isNew'] = 1;
        $this->db[] = $newRow;
    }
}