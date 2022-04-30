# Virtual SQL

Virtual SQL is a class-based abstraction layer for MySQL, designed to help with programmatically writing MySQL
queries.  
It allows working with query structures via a singular VirtualSqlQuery class, abstracting the actual MySQL syntax away
from consideration.

## Installation
```
composer require zethika/virtual-sql
```
## Table definitions

Virtual SQL provides a class (VirtualSqlTable) for defining a database table and its columns, which needs to be used
when working with queries towards those tables.  
These table definitions can be generated at runtime, by providing a PDO instance to the
VirtualSqlTableDefinitionGenerator Singleton.  
It can then extract the CREATE TABLE definition directly from the database and provide an instance representing a given
table.  
The CREATE TABLE statements extracted from the database is kept in memory, to minimize excess SQL queries from repeated calls to the same table.

```
use VirtualSql\Generator\VirtualSqlTableDefinitionGenerator;

try {
    $pdo = new PDO('mysql:host='.$_ENV['DB_HOST'].';dbname='.$_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASS']);
    $pdo->exec("SET NAMES utf8");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    trigger_error($e->getMessage(),E_USER_ERROR);
}

$generator = VirtualSqlTableDefinitionGenerator::getInstance();
$generator->init($pdo);

// A VirtualSql\Definition\VirtualSqlTable instance
$tableDefinition = $generator->generateTableDefinition('table_name');
```

## Queries

Queries are built using child classes of VirtualSqlQuery and is the programmatic interface, with which the runtime
builds the query.  
They provide a series of helper functions, depending on which type of query that's being built, for easier manipulation
of the query parts.

Query instances can be generated by providing the VirtualSqlQuery::factory method a type constant, and a VirtualSqlTable
instance for the base table.  
Supported types are TYPE_SELECT, TYPE_INSERT, TYPE_UPDATE and TYPE_DELETE

```
use VirtualSql\Query\VirtualSqlQuery;
$query = VirtualSqlQuery::factory(VirtualSqlQuery::TYPE_SELECT,$table);
```

### Query config parameter

A third parameter can be provided to the factory method, an associative array $config.  
This can be used to set most values in a query at instantiation.  

The possible keys for it depends on the specific Query type. All values set via the $config parameter can also be
manipulated via method calls after instantiation.

#### SELECT

```
$query = VirtualSqlQuery::factory(VirtualSqlQuery::TYPE_SELECT,$table, [
    // Array of VirtualSqlColumn instances, describing which columns to select
    'selects' => [
        $table->getColumn('some_column'),
        $table->getColumn('another_column')
    ],
    // Array of VirtualSqlJoin instances, describing which joins to perform
    // It's recommended using the query helper methods instead
    'joins' => [
        new VirtualSqlJoin($fromColumn, $toColumn)
    ],
    // VirtualSqlConditionSet instance representing the base condition set
    'where' => new VirtualSqlConditionSet(VirtualSqlConstant::OPERATOR_AND, $conditions),
    // int|null representing the LIMIT parameter
    'limit' => 10,
    // int|null representing the OFFSET parameter
    'offset' => 10
]);
```

#### INSERT

```
$query = VirtualSqlQuery::factory(VirtualSqlQuery::TYPE_INSERT,$table, [
    // Array of VirtualSqlColumn instances, describing which columns to insert into
    'columns' => $table->getColumns(),
    // Array of associative arrays describing the value sets being inserted
    'valueSets' => [
        [
            'column_1' => 'some_value',
            'column_2' => 'some_value,
        ],
        [
            'column_1' => 'some_value',
            'column_2' => 'some_value,
        ]
    ],
    // Array of VirtualSqlColumn instances, describing which columns should be updated in the ON DUPLICATE KEY UPDATE part
    // If none are provided, this query part will not be generated
    'onDuplicateUpdateColumns' => [
        $uuidTable->getColumn('blog_id'),
        $uuidTable->getColumn('resource_id'),
    ]
]);
```

#### UPDATE

```
$query = VirtualSqlQuery::factory(VirtualSqlQuery::TYPE_UPDATE,$table, [
    // Array of VirtualSqlColumn instances, describing which columns to update
    'columns' => $table->getColumns(),
    // Associative array describing the values being updated
    'values' => [
        'column_1' => 'some_value',
        'column_2' => 'some_value,
    ],
    // Array of VirtualSqlJoin instances, describing which joins to perform
    // It's recommended using the query helper methods instead
    'joins' => [
        new VirtualSqlJoin($fromColumn, $toColumn)
    ],
    // VirtualSqlConditionSet instance representing the base condition set
    'where' => new VirtualSqlConditionSet(VirtualSqlConstant::OPERATOR_AND, $conditions),
]);
```

#### DELETE

```
$query = VirtualSqlQuery::factory(VirtualSqlQuery::TYPE_DELETE,$table, [
    // Array of VirtualSqlJoin instances, describing which joins to perform
    // It's recommended using the query helper methods instead
    'joins' => [
        new VirtualSqlJoin($fromColumn, $toColumn)
    ],
    // VirtualSqlConditionSet instance representing the base condition set
    'where' => new VirtualSqlConditionSet(VirtualSqlConstant::OPERATOR_AND, $conditions),
    // int|null representing the LIMIT parameter
    'limit' => 10,
]);
```

## Full example

```
<?php
use VirtualSql\Generator\VirtualSqlTableDefinitionGenerator;
use VirtualSql\Query\VirtualSqlQuery;
use VirtualSql\QueryParts\VirtualSqlConditionSetBuilder;

require_once __DIR__.'/vendor/autoload.php';

try {
    $pdo = new PDO('mysql:host='.$_ENV['DB_HOST'].';dbname='.$_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASS']);
    $pdo->exec("SET NAMES utf8");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    trigger_error($e->getMessage(),E_USER_ERROR);
}

$generator = VirtualSqlTableDefinitionGenerator::getInstance();
$generator->init($pdo);

$table1 = $generator->generateTableDefinition('table_name');
$table2 = $generator->generateTableDefinition('another_table_name');

$query = VirtualSqlQuery::factory(VirtualSqlQuery::TYPE_SELECT,$table1);

// Adding an INNER JOIN statement, with a multidimensional condition on it.
$query->innerJoin(
    $table1->getColumn('id'),
    $table2->getColumn('table1_id'),
    VirtualSqlConditionSetBuilder::andX(
        VirtualSqlConditionSetBuilder::condition($table1->getColumn('some_column'),'?'),
        VirtualSqlConditionSetBuilder::condition($table1->getColumn('another_column'),'?'),
        VirtualSqlConditionSetBuilder::orX(
            VirtualSqlConditionSetBuilder::condition($table2->getColumn('a_third_column'),'?'),
            VirtualSqlConditionSetBuilder::condition($table2->getColumn('a_fourth_column'),'?')
        )
    )
);

// Add an additional, seperate where statement
$query->addWhere(VirtualSqlConditionSetBuilder::andX(
    VirtualSqlConditionSetBuilder::condition($table2->getColumn('second_table_column'),'some_value'),
    VirtualSqlConditionSetBuilder::condition($table1->getColumn('another_table_column'),'some_other_value')
));

// Define the selects by referencing specific columns via their table instance.
// If no selects are defined or this method is never called, "*" is the default
$query->setSelects(
    [
        $table1->getColumn('id'),
        $table2->getColumn('the_value_column_on_second_table')
    ]
);

// The actual SQL query string
$sql = $query->getSql();

// An associative array of all the named parameters used in the SQL
$parameters = $query->getNamedParameters();
```

## Helpers
Depending on which type of VirtualSqlQuery is being worked, there are a number of helper methods present on the instance to add / manipulate the various parts.  
For example to create joins on a select query, there is "innerJoin", "leftJoin", "rightJoin" and "outerJoin" methods which take a $from & $to column, as well as optionally a condition set

## Conditions
Virtual SQL uses VirtualSqlCondition instances to describe individual conditions and VirtualSqlConditionSet to describe sets of conditions.  
To help build them Virtual SQL provides the class VirtualSqlConditionSetBuilder which has a series of static methods to build conditions and condition sets

```
$conditionSet = VirtualSqlConditionSetBuilder::andX(
    VirtualSqlConditionSetBuilder::condition($table1->getColumn('some_column'),'?'),
    VirtualSqlConditionSetBuilder::condition($table1->getColumn('another_column'),'?'),
    VirtualSqlConditionSetBuilder::orX(
        VirtualSqlConditionSetBuilder::condition($table2->getColumn('a_third_column'),'?'),
        VirtualSqlConditionSetBuilder::condition($table2->getColumn('a_fourth_column'),'?')
    )
)
```

After instantiation, conditions may still be manipulated and added / removed to, as would be expected.
