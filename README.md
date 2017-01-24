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
