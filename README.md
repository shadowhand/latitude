Latitude Query Builder
======================

[![Latest Stable Version](https://img.shields.io/packagist/v/latitude/latitude.svg)](https://packagist.org/packages/latitude/latitude)
[![License](https://img.shields.io/packagist/l/latitude/latitude.svg)](https://github.com/shadowhand/latitude/blob/master/LICENSE)
[![Build Status](https://travis-ci.org/shadowhand/latitude.svg)](https://travis-ci.org/shadowhand/latitude)
[![Code Coverage](https://scrutinizer-ci.com/g/shadowhand/latitude/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/shadowhand/latitude/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/shadowhand/latitude/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/shadowhand/latitude/?branch=master)

A SQL query builder with zero dependencies. Attempts to be [PSR-1](http://www.php-fig.org/psr/psr-1/),
[PSR-2](http://www.php-fig.org/psr/psr-2/), and [PSR-4](http://www.php-fig.org/psr/psr-4/) compliant.

Latitude is heavily influenced by the design of [Aura.SqlQuery](https://github.com/auraphp/Aura.SqlQuery).

## Install

```
composer require latitude/latitude
```

## Usage

Latitude includes both a query builder and a powerful set of escaping helpers.
The query builder allows the fluent generation of `SELECT`, `INSERT`, `UPDATE`,
and `DELETE` statements. The escaping helpers assist in protecting against SQL
injection and identifier quoting for MySQL, SQL Server, Postgres, and other
databases that follow SQL standards.

## Examples

### INSERT

```php
use Latitude\QueryBuilder\InsertQuery;

$insert = InsertQuery::make('users', [
    'username' => 'jsmith',
]);

echo $select->sql();
// INSERT INTO users (username) VALUES (?)

print_r($insert->params());
// ["jsmith"]
```

There is also a Postgres extension that allows the use of the `RETURNING` statement:

```php
use Latitude\QueryBuilder\Postgres\InsertQuery;

$insert = InsertQuery::make(...)
    ->returning([
        'id',
    ]);

echo $insert->sql();
// INSERT INTO users (username) VALUES (?) RETURNING id
```

### UPDATE

```php
use Latitude\QueryBuilder\UpdateQuery;
use Latitude\QueryBuilder\Conditions;

$update = UpdateQuery::make('users', [
    'username' => 'mr-smith',
])
->with(
    Conditions::make('id = ?', 5)
);

echo $select->sql();
// UPDATE users SET username = ? WHERE id = ?

print_r($update->params());
// ["mr-smith", 5]
```

There is also a Postgres extension that allows the use of the `RETURNING` statement:

```php
use Latitude\QueryBuilder\Postgres\UpdateQuery;

$update = UpdateQuery::make(...)
    ->returning([
        'updated_at',
    ]);

echo $update->sql();
// UPDATE users SET username = ? WHERE id = ? RETURNING updated_at
```

### DELETE

```php
use Latitude\QueryBuilder\DeleteQuery;
use Latitude\QueryBuilder\Conditions;

$delete = DeleteQuery::make('users')
->with(
    Conditions::make('last_login IS NULL')
);

echo $select->sql();
// DELETE FROM users WHERE last_login IS NULL

print_r($delete->params());
// []
```

There is also a Postgres extension that allows the use of the `RETURNING` statement:

```php
use Latitude\QueryBuilder\Postgres\DeleteQuery;

$delete = DeleteQuery::make(...)
    ->returning([
        'id',
    ]);

echo $delete->sql();
// DELETE FROM users WHERE last_login IS NULL RETURNING id
```

### Conditions

The conditions builder acts as both a dynamic condition builder and a parameter
holder.

```php
use Latitude\QueryBuilder\Conditions;

$statement = Conditions::make('id = ?', 5)
    ->andWith('last_login IS NULL');

echo $statement->sql();
// id = ? AND last_login IS NULL

print_r($statement->params());
// [5]
```

#### Grouping Conditions

Conditions can also produce groupings:

```php
$statement = Conditions::make()
    ->group()
        ->with('subtotal > ?')
        ->andWith('taxes > 0')
    ->end()
    ->orGroup()
        ->with('cost > ?')
        ->andWith('cancelled = true')
    ->end();

echo $statement->sql();
// (subtotal > ? AND taxes > 0) OR (cost > ? AND cancelled = true)
```

**Note:** Be sure to call `end()` to close the group, or you may get unexpected
query results!

#### IN conditions

Because PDO does not have an easy way to handle array values for `IN` conditions,
the builder introduces a special `?*` placeholder that will be expanded when using
the `in()`, `andIn()`, and `orIn()` methods.

```php
use Latitude\QueryBuilder\Conditions;

$ids = [1, 12, 5];

$statement = Conditions::make()
    ->in('id IN ?*', $ids);

echo $statement->sql();
// role IN (?, ?, ?)

print_r($statement->params());
// [1, 12, 5]
```

**Note:** Expansion of `?*` for a variable number of placeholders only works with
the `in*()` methods. Using `?*` with any other method will produce an invalid query!

#### LIKE Conditions

```php
use Latitude\QueryBuilder\Escape as e;

$statement = Conditions::make()
    ->with('name LIKE ?', e::likeAny('Smith'));

print_r($statement->params());
// ['%Smith%'];
```

Note the usage of the `e::likeAny()` method here. This will ensure that special
characters in the input string are escaped properly, and then add `%` wildcards
around the input.

If you want to add the `%` wildcards manually, the `e::like()` method will only
only escape the input:

```php
$search = '%' . e::like($search);
// or
$search = str_replace(' ', '%', e::like($search));
```

There is also a MSSQL extension that will escape character ranges:

```php
use Latitude\QueryBuilder\SqlServer\Escape as e;

echo e::like('[range] test');
// "\\[range\\] test"
```

### Boolean and Null Values

In `INSERT` and `UPDATE` queries, boolean and null values will be added directly
the query, rather than as placeholders. This is due to the fact that
`PDOStatement::execute($params)` will attempt to cast all parameters to strings,
which does not work correctly with booleans or nulls.

See [`PDOStatement::execute` documentation](http://php.net/manual/pdostatement.execute.php)
for more information.

## Why use Latitude instead of X?

Many query builders depend directly on PDO or use complicated condition syntax
that is, in my opinion, less than ideal. Very few require PHP 7 strict type hinting.

A couple of query builders require specific mention, as they are quite good.

### Aura.SqlQuery

The external interface of Aura.SqlQuery is fantastic and Latitude borrows heavily
on the ergonomics of it. However, there are two very distinct flaws in SqlQuery
that I am unhappy with:

1. It does not allow for sequential `?` placeholders. While this is a relatively
   minor thing, it forces the parameters to be bound in a very specific way.
2. It defers handling of array values for `IN` conditions. This isn't a problem
   when using the Aura PDO wrapper [Aura.Sql](https://github.com/auraphp/Aura.Sql),
   which unpacks array values into a list of values. If you choose not use Aura.Sql,
   it becomes much more complicated.

Due to these two issues that cannot be easily patched out, and because there is no
sign of a PHP7 version of Aura components, I decided to write my own.

## License

Latitude is licensed under [MIT](LICENSE.md) and can be used for any personal or
commercial project. If you really like it, feel free to buy me a beer sometime!
