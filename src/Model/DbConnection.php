<?php

declare(strict_types=1);

namespace TpElections\Model;

class DbConnection
{
    private static DbConnection $instance;

    public static function getOrCreateInstance(): DbConnection
    {
        if (isset(static::$instance) === false) {
            static::$instance = new static(
                pdo: new \PDO(
                    dsn: 'pgsql:host=db;dbname=elections',
                    username: 'usr_elections',
                    password: '!P@ssw0rD!',
                    options: [
                        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                    ]
                )
            );
        }

        return static::$instance;
    }

    private function __construct(
        public readonly \PDO $pdo,
    ) {
    }
}
