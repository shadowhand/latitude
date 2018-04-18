---
layout: default
title: Delete Queries
---

It is recommended to always include a `WHERE` statement:

```php
$query = $factory
    ->delete('users')
    ->where(field('login_at')->isNull())
    ->compile();

$query->sql(); // DELETE FROM "users" WHERE "login_at" IS NULL
$query->params(); // []
```

It is also possible to provide a `LIMIT`:

```php
$query = $factory
    ->delete('users')
    ->limit(5)
    ->compile();

$query->sql(); // DELETE FROM "users" LIMIT 5
$query->params(); // []
```

**[Back](../)**
