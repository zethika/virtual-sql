<?php

use PHPUnit\Framework\TestCase;

abstract class AbstractVirtualSqlTestCase extends TestCase
{
    const PLAIN_TABLE_WITH_TWO_COLUMNS = "CREATE TABLE `plain_table` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `other_column` int(11),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

    const COMPOSITE_TABLE_WITH_2_COMPOSITE_PRIMARY_KEYS = "CREATE TABLE `composite_table` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `other_primary` int(11),
  `other_column` int(11),
  PRIMARY KEY (`id`,`other_primary`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

    const TABLE_WITH_ALL_TYPES = "CREATE TABLE `types_table` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tinyint_type` tinyint(4) DEFAULT NULL,
  `smallint_type` smallint(11) DEFAULT NULL,
  `mediumint_type` mediumint(11) DEFAULT NULL,
  `int_type` int(11) DEFAULT NULL,
  `bigint_type` bigint(11) DEFAULT NULL,
  `float_type` float DEFAULT NULL,
  `double_type` double DEFAULT NULL,
  `decimal_type` decimal(11,0) DEFAULT NULL,
  `bit_type` bit(11) DEFAULT NULL,
  `char_type` char(255) DEFAULT NULL,
  `tinytext_type` tinytext,
  `text_type` text,
  `mediumtext_type` mediumtext,
  `longtext_type` longtext,
  `tinyblob_type` tinyblob,
  `mediumblob_type` mediumblob,
  `blob_type` blob,
  `longblob_type` longblob,
  `binary_type` binary(1) DEFAULT NULL,
  `varbinary_type` varbinary(1) DEFAULT NULL,
  `enum_type` enum('x-small','small') DEFAULT NULL,
  `set_type` set('set-1','set-2') DEFAULT NULL,
  `date_type` date DEFAULT NULL,
  `datetime_type` datetime DEFAULT NULL,
  `timestamp_type` timestamp NULL DEFAULT NULL,
  `time_type` time DEFAULT NULL,
  `year_type` year(4) DEFAULT NULL,
  `geometry_type` geometry DEFAULT NULL,
  `point_type` point DEFAULT NULL,
  `linestring_type` linestring DEFAULT NULL,
  `polygon_type` polygon DEFAULT NULL,
  `multipoint_type` multipoint DEFAULT NULL,
  `multilinestring_type` multilinestring DEFAULT NULL,
  `multipolygon_type` multipolygon DEFAULT NULL,
  `geometrycollection_type` geometrycollection DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

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