---
layout: default
title: Cheatsheet
---

# Cheatsheet

- [Criteria](#criteria)
- [Expressions](#expressions)
- [Aliases](#aliases)
- [Functions](#functions)
- [Ordering](#ordering)
- [Identifiers](#identifiers)
- [Parameters](#parameters)
- [Lists](#lists)

## [](#criteria)Criteria

```php
use function Latitude\QueryBuilder\field;

// "users"."id" = ?
field('users.id')->eq(100)
// "users"."birthday" > ?
field('users.birthday')->gt('2000-01-01')
// "users"."last_login" BETWEEN ? AND ?
field('users.last_login')->between($yesterday, $today)
// "users"."role" NOT IN (?, ?)
field('users.role')->notIn('admin', 'moderator')
// "countries"."id" IN (?)
field('countries.id')->in($select)
// "total" > ?
field('total')->gt(9000)
// "salary" <= ?
field('salary')->lte(3000)
// "deleted_at" IS NULL
field('deleted_at')->isNull();
// "parent_id" IS NOT NULL
field('parent_id')->isNotNull();
```

```php
use function Latitude\QueryBuilder\search;

// "username" LIKE '%admin%'
search('username')->contains('admin')
// "first_name" LIKE 'john%'
search('first_name')->begins('john')
// "last_name" NOT LIKE '%rump'
search('last_name')->notEnds('rump')
```

```php
use function Latitude\QueryBuilder\on;

// "countries"."id" = "users"."country_id"
on('countries.id', 'users.country_id')
```

```php
use function Latitude\QueryBuilder\group;

// ("username" = ? OR "first_name" = ?) AND "is_active" = ?
group(
    field('username')->eq('tom')
        ->or(field('first_name')->eq('Tom'))
)->and(field('is_active')->eq(1))
```

### [](#expressions)Expressions

_All expressions are written in [sprintf](http://php.net/sprintf) format, where
any `%s` variable will be replaced with a statement. Statements can be any object
implementing `StatementInterface`, including queries and expressions._

```php
use function Latitude\QueryBuilder\express;

// "execute_at" <= NOW()
express('%s <= NOW()', identify('execute_at'))
```

_Unlike the `express()` helper, `criteria()` will produce a `CriteriaInterface`._

```php
use function Latitude\QueryBuilder\criteria;

// "orders"."total" > ?
criteria('%s > %s', identify('orders.total'), 100.00)
```

```php
use function Latitude\QueryBuilder\literal;

// "orders"."is_complete" = 1
criteria('%s = %d', identify('orders.is_complete'), literal(1))
```

## [](#aliases)Aliases

```php
use function Latitude\QueryBuilder\alias;

// "users"."id" AS "uid"
alias('users.id', 'uid')
```

## [](#functions)Functions

```php
use function Latitude\QueryBuilder\func;

// COUNT("users"."id")
func('COUNT', 'users.id')
// CONCAT("first_name", "last_name")
func('CONCAT', 'first_name', 'last_name')
```

_By default functions assume identifiers as parameters, use `param()` for scalar values._


```php
use function Latitude\QueryBuilder\func;
use function Latitude\QueryBuilder\param;

// POINT(?, ?)
func('POINT', param(1), param(2))
```

## [](#ordering)Ordering

```php
use function Latitude\QueryBuilder\order;

// "total" DESC
order('total', 'desc');
```

## [](#identifiers)Identifiers

```php
use function Latitude\QueryBuilder\identify;
use function Latitude\QueryBuilder\identifyAll;

// "users"."username"
identify('users.username')
// "country"
identify('country')
/* produces an array of identifiers */
identifyAll(['id', 'username'])
```

## [](#parameters)Parameters

```php
use function Latitude\QueryBuilder\param;
use function Latitude\QueryBuilder\paramAll;

// ?
param(15)
/* produces an array of parameters */
paramAll(['a', 5, 20.00])
```

## [](#lists)Lists

```php
use function Latitude\QueryBuilder\listing;

// ?, ?, ?, ?, ?
listing([1, 1, 2, 3, 5])
// "id", "username", "email"
listing(identifyAll(['id', 'username', 'email']))
```

**[Back](../)**
