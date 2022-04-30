<?php

namespace VirtualSql;

use PDO;
use PDOException;

class VirtualSqlQueryHelper
{
    /**
     * @var PDO
     */
    private PDO $pdo;

    /**
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @param string $sql
     * @param array $parameters
     */
    public function execute(string $sql, array $parameters = []): void
    {
        try
        {
            $q = $this->pdo->prepare($sql);
            $q->execute($parameters);
        } catch (PDOException $e)
        {
            trigger_error($e->getMessage(), E_USER_ERROR);
        }
    }

    /**
     * @param string $sql
     * @param array $parameters
     * @return array|false
     */
    public function fetch(string $sql, array $parameters = [])
    {
        try
        {
            $q = $this->pdo->prepare($sql);
            $q->execute($parameters);
            return $q->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e)
        {
            trigger_error($e->getMessage(), E_USER_ERROR);
        }
    }

    /**
     * @param string $sql
     * @param array $parameters
     * @return array|false
     */
    public function fetchAll(string $sql, array $parameters = [])
    {
        try
        {
            $q = $this->pdo->prepare($sql);
            $q->execute($parameters);
            return $q->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e)
        {
            trigger_error($e->getMessage(), E_USER_ERROR);
        }
    }
}
