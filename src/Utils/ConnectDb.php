<?php

/**
 * (c) Adrien PIERRARD
 */

namespace MyWebsite\Utils;

use PDO;

/**
 * Singleton Class ConnectDb.
 */
class ConnectDb
{
    /**
     * A self Instance
     *
     * @var null
     */
    protected static $instance = null;

    /**
     * A PDO Instance connection to db
     *
     * @var PDO
     */
    protected $conn;

    /**
     * ConnectDb constructor.
     */
    private function __construct()
    {
        $this->conn = new PDO(
            "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']}",
            $_ENV['DB_USER'],
            $_ENV['DB_PASS']
        );
    }

    /**
     * Get a self Instance
     *
     * @return ConnectDb
     */
    public static function getInstance(): ConnectDb
    {
        if (!self::$instance) {
            self::$instance = new ConnectDb();
        }

        return self::$instance;
    }

    /**
     * Getter Connection
     *
     * @return PDO
     */
    public function getConnection(): PDO
    {
        return $this->conn;
    }
}
