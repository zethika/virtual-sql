<?php

use PHPUnit\Framework\TestCase;

abstract class AbstractVirtualSqlTestCase extends TestCase
{
    protected static $pdo = null;

    /**
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        // Generate the PDO instance, we should only ever want one of these, and it should be shared across all test cases, hence a static, self initializing approach.
        if(self::$pdo === null)
        {
            $dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/..');
            $dotenv->load();

            try {
                $pdo = new PDO('mysql:host='.$_ENV['TEST_DB_HOST'].';dbname='.$_ENV['TEST_DB_NAME'], $_ENV['TEST_DB_USER'], $_ENV['TEST_DB_PASS']);
                $pdo->exec("SET NAMES utf8");
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                trigger_error($e->getMessage(),E_USER_ERROR);
            }

            self::$pdo = $pdo;
        }
    }
}