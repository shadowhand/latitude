---
layout: default
title: Introduction
---

# Introduction

[![Latest Stable Version](https://img.shields.io/packagist/v/latitude/latitude.svg)](https://packagist.org/packages/latitude/latitude)
[![License](https://img.shields.io/github/license/shadowhand/latitude.svg)](https://github.com/shadowhand/latitude/blob/master/LICENSE)
[![Build Status](https://img.shields.io/travis/shadowhand/latitude/master.svg)](https://travis-ci.org/shadowhand/latitude)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/shadowhand/latitude/master.svg)](https://scrutinizer-ci.com/g/shadowhand/latitude/?branch=master)
[![Code Quality](https://img.shields.io/scrutinizer/g/shadowhand/latitude.svg)](https://scrutinizer-ci.com/g/shadowhand/latitude/?branch=master)

Latitude is a SQL query builder with zero dependencies and a fluent interface.
It supports most of [SQL-92](https://en.wikipedia.org/wiki/SQL-92) as well as
database specific functionality:

```php
use Latitude\QueryBuilder\Engine\CommonEngine;
use Latitude\QueryBuilder\QueryFactory;

use function Latitude\QueryBuilder\field;

$factory = new QueryFactory(new CommonEngine());
$query = $factory
    ->select('id', 'username')
    ->from('users')
    ->where(field('id')->eq(5))
    ->compile();

$query->sql(); // SELECT "id" FROM "users" WHERE "id" = ?
$query->params(); // [5]
```

# [](#documentation)Documentation

Latitude includes both a query builder and a powerful set of escaping helpers.
The query builder allows the fluent generation of `SELECT`, `INSERT`, `UPDATE`,
and `DELETE` statements. The escaping helpers assist in protecting against SQL
injection and identifier quoting for MySQL, SQL Server, Postgres, and other
databases that follow SQL standards.

Getting Started

- [Installation](install)
- [Quick Reference](cheatsheet)
- [Engines](engines)

Query Types

- [SELECT](query-select)
- [INSERT](query-insert)
- [UPDATE](query-update)
- [DELETE](query-delete)

## Booleans and Nulls

In `INSERT` and `UPDATE` queries, boolean and null values will be added directly
the query, rather than as placeholders. This is due to the fact that
`PDOStatement::execute($params)` will attempt to cast all parameters to strings,
which does not work correctly with booleans or nulls.

See [`PDOStatement::execute` documentation](http://php.net/manual/pdostatement.execute.php)
for more information.
