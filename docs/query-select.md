---
layout: default
title: Select Queries
---

A simple select will include all columns:

```php
$query = $factory
    ->select()
    ->from('users')
    ->limit(100)
    ->compile();

$query->sql(); // SELECT * FROM "users" LIMIT 100
$query->params(); // []
```

Specific columns can be selected:

```php
$query = $factory
    ->select('id', 'username')
    ->from('users')
    ->compile();

$query->sql(); // SELECT "id", "username" FROM "users"
$query->params(); // []
```

Additional columns can be added:

```php
$query = $factory
    ->select('id', 'username')
    ->addColumns('password')
    ->from('users')
    ->compile();

$query->sql(); // SELECT "id", "username", "password" FROM "users"
$query->params(); // []
```

As well as additional tables:

```php
$query = $factory
    ->select('users.username', 'groups.name')
    ->from('users')
    ->addFrom('groups')
    ->compile();

$query->sql(); // SELECT "users"."username", "groups"."name" FROM "users", "groups"
$query->params(); // []
```

# WHERE

Criteria can be applied to the `WHERE` condition:

```php
$query = $factory
    ->select()
    ->from('countries')
    ->where(field('language')->eq('EN'))
    ->compile();

$query->sql(); // SELECT * FROM "countries" WHERE "language" = ?
$query->params(); // ['EN']
```

Additional criteria can be added using `andWhere()` and `orWhere()`:

```php
$query = $factory
    ->select()
    ->from('users')
    ->where(field('id')->gt(1))
    ->orWhere(field('login_at')->isNull())
    ->orWhere(field('is_inactive')->eq(1))
    ->compile();
```

Would produce:

```sql
SELECT *
FROM "users"
WHERE "id" > ?
OR "login_at" IS NULL
OR "is_inactive" = ?
```

# JOIN

Joins are added in a similar way:

```php
$query = $factory
    ->select('u.id', 'c.name')
    ->from(alias('users', 'u'))
    ->join(alias('countries', 'c'), on('u.country_id', 'c.id'))
    ->compile();
```

Would produce:

```sql
SELECT "u"."id", "c"."name"
FROM "users" AS "u"
JOIN "countries" AS "c" ON "u"."country_id" = "c"."id"
```

The join type can also be specified as the third parameters or one of the helpers
can be used for common types:

- `leftJoin()`
- `rightJoin()`
- `innerJoin()`
- `fullJoin()`

# ORDER BY

Ordering can be applied:

```php
$query = $factory
    ->select()
    ->from('users')
    ->orderBy('username', 'asc');
```
Ordering can be reset:

```php
$query->orderBy(null);
```

# LIMIT and OFFSET

Limits and offsets can be applied:

```php
$query = $factory
    ->select()
    ->from('posts')
    ->offset(10)
    ->limit(10)
    ->compile();
```

_**Note:** When using the SQL Server engine an offset **must** be defined for
the limit to be applied! Use `offset(0)` when no offset is desired._

**[Back](../)**
