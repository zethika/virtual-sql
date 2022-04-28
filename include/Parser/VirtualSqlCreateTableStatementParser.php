<?php

namespace VirtualSql\Parser;

use VirtualSql\Definition\VirtualSqlColumn;
use VirtualSql\Definition\VirtualSqlTable;
use VirtualSql\Exceptions\InvalidStatementPartException;
use VirtualSql\Traits\SingletonTrait;
use VirtualSql\VirtualSql;

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
	public function parseStatement(string $statement)
	{
		$this->statement = $statement;
		$this->columns = [];

		preg_match('/CREATE TABLE `(.*?)` \((.*)\)/is',$statement, $matches);
		if(count($matches) !== 3)
			throw new InvalidStatementPartException('String is not a recognized create statement');

		$this->parseColumns($matches[2]);
		return new VirtualSqlTable($matches[1],$this->columns);
	}

	/**
	 *
	 */
	private function parseColumns(string $columnsString)
	{
		$columns = explode("\n",$columnsString);
		foreach ($columns as $column){
			preg_match('/(.*) `(.*?)` (.*?)[ ,]/is',$column,$matches);
			if(count($matches) === 4)
			{
				$this->columns[$matches[2]] = $this->populateColumn($column, $matches[2], $matches[3]);
			}
			else if(trim($column) !== '')
			{
				preg_match('/PRIMARY KEY \(`(.*)`\)/is',$column,$matches);
				if(count($matches) === 2 && isset($this->columns[$matches[1]]))
					$this->columns[$matches[1]]->addExtra(VirtualSql::EXTRA_PRIMARY_KEY);
			}
		}
	}

	/**
	 * @param string $columnString
	 * @param string $name
	 * @param string $typeDeclaration
	 * @return VirtualSqlColumn
	 */
	private function populateColumn(string $columnString, string $name, string $typeDeclaration)
	{
		$type = $this->parseTypeDeclaration($typeDeclaration);

		return new VirtualSqlColumn(
			$name,
			$type['type'],
			$type['length'],
			str_contains($columnString, 'NOT NULL') === false,
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
		if(str_contains($columnString,VirtualSql::EXTRA_AUTO_INCREMENT))
			$extras[] = VirtualSql::EXTRA_AUTO_INCREMENT;

		if(str_contains($columnString,VirtualSql::EXTRA_UNIQUE))
			$extras[] = VirtualSql::EXTRA_UNIQUE;

		if(str_contains($columnString,VirtualSql::EXTRA_ON_UPDATE_CURRENT_TIMESTAMP))
			$extras[] = VirtualSql::EXTRA_ON_UPDATE_CURRENT_TIMESTAMP;

		return $extras;
	}

	/**
	 * @param string $columnString
	 * @return string|null
	 */
	private function parseDefaultValue(string $columnString): ?string
	{
		// No default value
		if(str_contains($columnString, 'DEFAULT') === false)
			return null;

		// Default value is not a string
		if(str_contains($columnString, 'DEFAULT \'') === false)
		{
			preg_match('/DEFAULT (.*?)[ ,]/',$columnString, $matches);
			return count($matches) === 2 ? $matches[1] : null;
		}

		$parts = explode('DEFAULT \'',$columnString);
		return substr(end($parts),0,mb_strrpos(end($parts),'\''));
	}

	/**
	 * @param string $typeDeclaration
	 */
	private function parseTypeDeclaration(string $typeDeclaration)
	{
		$pos = strpos($typeDeclaration,'(');
		return ($pos !== false) ? [
			'type' => substr($typeDeclaration,0,$pos),
			'length' => substr($typeDeclaration,$pos+1,-1)
		] : ['type' => $typeDeclaration, 'length' => null];
	}

	/**
	 * Extracts the table name from the create string
	 */
	private function determineTableName(){

	}
}
