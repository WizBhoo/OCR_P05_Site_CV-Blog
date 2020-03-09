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
    private static $instance = null;

    /**
     * A PDO Instance connection to db
     *
     * @var PDO
     */
    private $conn;

    /**
     * MySQL Host Name
     *
     * @var string
     */
    private $host = 'mysql-server.localhost';

    /**
     * MySQL db username
     *
     * @var string
     */
    private $user = 'root';

    /**
     * MySQL db pass
     *
     * @var string
     */
    private $pass = 'root';

    /**
     * MySQL dbname
     *
     * @var string
     */
    private $name = 'monsite';

    /**
     * ConnectDb constructor.
     */
    private function __construct()
    {
        $this->conn = new PDO(
            "mysql:host={$this->host};dbname={$this->name}",
            $this->user,
            $this->pass
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
