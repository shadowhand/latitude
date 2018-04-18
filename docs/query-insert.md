---
layout: default
title: Insert Queries
---

Inserts can be performed with a single row:

```php
$query = $factory
    ->insert('places', [
        'name' => 'home',
        'address' => '123 Main St'
    ])
    ->compile();

$query->sql(); // INSERT INTO "places" ("name", "address") VALUES (?, ?)
$query->params(); // ['home', '123 Main St']
```

Or multiple rows:

```php
$query = $factory
    ->insert('users')
    ->columns('username', 'password')
    ->values('sally', password_hash('truck ice tiger', PASSWORD_DEFAULT))
    ->values('mark', password_hash('pop battery sound', PASSWORD_DEFAULT))
    ->compile();

$query->sql(); // INSERT INTO "users" ("username", "password") VALUES (?, ?), (?, ?)
$query->params(); // ['sally', <hash>, 'mark', <hash>]
```

When using the Postgres engine `RETURNING` can be added:

```php
$query = $factory
    ->insert('friends', [
        'user_id' => 11,
        'friend_id' => 30,
    ])
    ->returning('id')
    ->compile();

$query->sql(); // INSERT INTO "friends" ("user_id", "friend_id") VALUES (?, ?) RETURNING "id"
$query->params(); // [11, 30]
```

**[Back](../)**
