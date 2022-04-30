<?php

namespace VirtualSql\Parser;

use VirtualSql\Definition\VirtualSqlColumn;
use VirtualSql\Definition\VirtualSqlTable;
use VirtualSql\Exceptions\InvalidStatementPartException;
use VirtualSql\Traits\SingletonTrait;
use VirtualSql\VirtualSqlConstant;

class VirtualSqlCreateTableStatementParser
{
    use SingletonTrait;

    /**
     * @var string
     */
    private string $statement;

    /**
     * @var VirtualSqlColumn[]
     */
    private array $columns;

    /**
     * @throws InvalidStatementPartException
     */
    public function parseStatement(string $statement): VirtualSqlTable
    {
        $this->statement = $statement;
        $this->columns = [];

        preg_match('/CREATE TABLE `(.*?)` \((.*)\)/is', $statement, $matches);
        if (count($matches) !== 3)
            throw new InvalidStatementPartException('String is not a recognized create statement');

        $this->parseColumns($matches[2]);
        return new VirtualSqlTable($matches[1], $this->columns);
    }

    /**
     *
     */
    private function parseColumns(string $columnsString)
    {
        $columns = explode("\n", $columnsString);
        foreach ($columns as $column)
        {
            $column = trim($column);
            if (strpos($column, VirtualSqlConstant::EXTRA_PRIMARY_KEY) !== false)
            {
                $this->parsePrimaryKeyColumn($column);
            }
            else if (strpos($column, VirtualSqlConstant::EXTRA_UNIQUE) !== false)
            {
                $this->parseUniqueColumn($column);
            }
            else
            {
                preg_match('/`(.*?)` (.*?)[ ,]/is', $column, $matches);
                if (count($matches) === 3)
                    $this->columns[$matches[1]] = $this->populateColumn($column, $matches[1], $matches[2]);
            }
        }
    }

    /**
     * @param string $column
     */
    private function parseUniqueColumn(string $column)
    {
        preg_match('/UNIQUE KEY `(.*)` \((.*)\),/is', $column, $matches);
        if (count($matches) !== 3)
            return;

        $columns = explode(',', str_replace('`', '', $matches[2]));
        foreach ($columns as $columnName)
        {
            if (isset($this->columns[$columnName]))
                $this->columns[$columnName]->addExtra(VirtualSqlConstant::EXTRA_UNIQUE);
        }
    }

    /**
     * @param string $column
     */
    private function parsePrimaryKeyColumn(string $column)
    {
        preg_match('/PRIMARY KEY \(`(.*)`\)/is', $column, $matches);
        if (count($matches) === 2 && isset($this->columns[$matches[1]]))
            $this->columns[$matches[1]]->addExtra(VirtualSqlConstant::EXTRA_PRIMARY_KEY);
    }

    /**
     * @param string $columnString
     * @param string $name
     * @param string $typeDeclaration
     * @return VirtualSqlColumn
     */
    private function populateColumn(string $columnString, string $name, string $typeDeclaration): VirtualSqlColumn
    {
        $type = $this->parseTypeDeclaration($typeDeclaration);

        return new VirtualSqlColumn(
            $name,
            $type['type'],
            $type['length'],
            strpos($columnString, 'NOT NULL') === false,
            $this->parseDefaultValue($columnString),
            $this->parseExtras($columnString)
        );
    }

    /**
     * @param string $columnString
     * @return array|null
     */
    private function parseExtras(string $columnString): ?array
    {
        $extras = [];
        if (strpos($columnString, VirtualSqlConstant::EXTRA_AUTO_INCREMENT) !== false)
            $extras[] = VirtualSqlConstant::EXTRA_AUTO_INCREMENT;

        if (strpos($columnString, VirtualSqlConstant::EXTRA_UNIQUE) !== false)
            $extras[] = VirtualSqlConstant::EXTRA_UNIQUE;

        if (strpos($columnString, VirtualSqlConstant::EXTRA_ON_UPDATE_CURRENT_TIMESTAMP) !== false)
            $extras[] = VirtualSqlConstant::EXTRA_ON_UPDATE_CURRENT_TIMESTAMP;

        return $extras;
    }

    /**
     * @param string $columnString
     * @return string|null
     */
    private function parseDefaultValue(string $columnString): ?string
    {
        // No default value
        if (strpos($columnString, 'DEFAULT') === false)
            return null;

        // Default value is not a string
        if (strpos($columnString, 'DEFAULT \'') === false)
        {
            preg_match('/DEFAULT (.*?)[ ,]/', $columnString, $matches);
            return count($matches) === 2 ? $matches[1] : null;
        }

        $parts = explode('DEFAULT \'', $columnString);
        return substr(end($parts), 0, mb_strrpos(end($parts), '\''));
    }

    /**
     * @param string $typeDeclaration
     * @return array
     */
    private function parseTypeDeclaration(string $typeDeclaration): array
    {
        $pos = strpos($typeDeclaration, '(');
        return ($pos !== false) ? [
            'type' => substr($typeDeclaration, 0, $pos),
            'length' => substr($typeDeclaration, $pos + 1, -1)
        ] : ['type' => $typeDeclaration, 'length' => null];
    }
}
