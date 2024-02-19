<?php

namespace Config;

use CodeIgniter\Database\Config;

/**
 * Database Configuration
 */
class Database extends Config
{
    /**
     * The directory that holds the Migrations
     * and Seeds directories.
     */
    public string $filesPath = APPPATH . 'Database' . DIRECTORY_SEPARATOR;

    /**
     * Lets you choose which connection group to
     * use if no other is specified.
     */
    public string $defaultGroup = 'default';

    /**
     * The default database connection.
     */
    public array $default = [
        'DSN'      => '',
        'hostname' => 'localhost',
        'username' => '',
        'password' => '',
        'database' => '',
        'DBDriver' => 'MySQLi',
        'DBPrefix' => '',
        'pConnect' => false,
        'DBDebug'  => true,
        'charset'  => 'utf8',
        'DBCollat' => 'utf8_general_ci',
        'swapPre'  => '',
        'encrypt'  => false,
        'compress' => false,
        'strictOn' => false,
        'failover' => [],
        'port'     => 3306,
    ];

    public function __construct()
    {
        parent::__construct();
        foreach(["default"] as $dbGroup){
            $this->$dbGroup["hostname"] = env("database.{$dbGroup}.hostname");
            $this->$dbGroup["username"] = env("database.{$dbGroup}.username");
            $this->$dbGroup["password"] = env("database.{$dbGroup}.password");
            $this->$dbGroup["database"] = env("database.{$dbGroup}.database");
            $this->$dbGroup["port"] = env("database.{$dbGroup}.port");
            $this->$dbGroup["charset"] = env("database.{$dbGroup}.charset");
            $this->$dbGroup["DBCollat"] = env("database.{$dbGroup}.DBCollat");
            $this->$dbGroup["DBDriver"] = "MySQLi";
            $this->$dbGroup["DBPrefix"] = "";
            $this->$dbGroup["DBDebug"] = true;
            $this->$dbGroup["swapPre"] = "";
            $this->$dbGroup["encrypt"] = false;
            $this->$dbGroup["compress"] = false;
            $this->$dbGroup["strictOn"] = false;
            $this->$dbGroup["failover"] = [];
        }
        // Ensure that we always set the database group to 'tests' if
        // we are currently running an automated test suite, so that
        // we don't overwrite live data on accident.
        if (ENVIRONMENT === 'testing') {
            $this->defaultGroup = 'tests';
        }
    }
}
