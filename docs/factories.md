---
layout: default
title: Engines
---

# Query Factories

Since version 4.5.0, Latitude provides engine specific query factories with proper type hints, for better type safety, IDE support and static analysis.
There is no need to pass the engine to the Factory constructor anymore.

## Example usage

```php
use Latitude\QueryBuilder\QueryFactory\SqliteQueryFactory;

$factory = new SqliteQueryFactory();
$query = $factory->insert('users', ['name' => 'Alice']);
// $query instanceof Latitude\QueryBuilder\Query\Sqlite\InsertQuery === true
```

Since `$query` is now a specific type, your IDE will provide autocomplete for `$query->update()->onConflictDoUpdate()`.

## Available factories

- SqliteQueryFactory
- MySqlQueryFactory
- PostgresQueryFactory
- SqlServerQueryFactory

You may also use:
- CommonQueryFactory for generic SQL-92 databases
- BasicQueryFactory: not for production use, since no escaping is performed

## Migration from version up to 4.4.x

No BC break has been introduced. You may still use the old QueryFactory and instantiate it with the engine.

However, you should use the new factories to get better type safety and better IDE support.

Before (deprecated):
```php
use Latitude\QueryBuilder\Engine\SqliteEngine;
use Latitude\QueryBuilder\QueryFactory;

$factory = new QueryFactory(new SqliteEngine());
```

After (recommended):
```php
use Latitude\QueryBuilder\QueryFactory\SqliteQueryFactory;

$factory = new SqliteQueryFactory();
```
