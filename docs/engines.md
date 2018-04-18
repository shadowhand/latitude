---
layout: default
title: Engines
---

# Supported Engines

## Basic Engine

The default engine is `Latitude\QueryBuilder\Engine\BasicEngine`. This engine
does not escape identifiers or provide any additional features.

_**Note:** It is **not** recommended to use this engine in production._

## Common Engine

The standard SQL-92 engine is `Latitude\QueryBuilder\Engine\CommonEngine`.
This engine escapes all identifiers using double quotes. No other additional
features are provided.

This engine is recommended for most databases without a more specific engine,
such as SQLite.

## MySQL Engine

The MySQL engine is `Latitude\QueryBuilder\Engine\MySqlEngine`. This engine
escapes all identifiers using backticks.

## Postgres Engine

The MySQL engine is `Latitude\QueryBuilder\Engine\PostgresEngine`. This engine
extends the `CommonEngine` to provide additional features:

- `RETURNING <identifier>` is added to `INSERT` and `UPDATE` queries

## SQL Server Engine

The MySQL engine is `Latitude\QueryBuilder\Engine\SqlServerEngine`. This engine
escapes all identifiers using brackets. It also escapes brackets used in `LIKE`
expressions, preventing the usage of character ranges.

_**Note**: This engine relies on features found in SQL Server 2012 and above._
