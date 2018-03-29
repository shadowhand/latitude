---
layout: default
title: Update Queries
---

It is recommended to always include a `WHERE` statement:

```php
$query = $factory
    ->update('places', [
        'address' => '555 Money Ave'
    ])
    ->where(field('name')->eq('work'))
    ->compile();

$query->sql(); // UPDATE "places" SET "address" = ? WHERE "name" = ?
$query->params(); // ['555 Money Ave', 'work']
```

When using the Postgres engine `RETURNING` can be added:

```php
$query = $factory
    ->update('users', [
        'is_active' => false,
    ])
    ->where(field('login_at')->lt('2018-01-01'))
    ->returning('id')
    ->compile();

$query->sql(); // UPDATE "users" SET "is_active" = ? WHERE "login_at" < ? RETURNING "id"
$query->params(); // [false, '2018-01-01']
```

**[Back](../)**
