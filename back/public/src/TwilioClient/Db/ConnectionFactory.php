<?php
declare(strict_types=1);

namespace TwilioClient\DB;

use PDO;

class ConnectionFactory
{
    private const CHARSET = 'utf8';

    private PDO $connection;

    /**
     * @param $config array<string, int|string>
     */
    public function __construct(array $config)
    {
        $this->createConnection($config);
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }

    /**
     * @param $config array<string, int|string>
     */
    private function createConnection(array $config): void
    {
        $line = sprintf(
            '%s:host=%s;port=%d;dbname=%s;charset=%s',
            $config['databaseDriver'],
            $config['databaseHost'],
            $config['databasePort'],
            $config['databaseName'],
            self::CHARSET,
        );

        $this->connection = new PDO(
            $line,
            $config['databaseUser'],
            $config['databasePassword'],
            [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"],
        );
    }
}
