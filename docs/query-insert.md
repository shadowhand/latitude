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

When using the Postgres or SQLite engine `RETURNING` can be added:

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

MySQL, Postgres, and SQLite support native constraint handling in insert queries.

MySQL:

Ignore on constraint violation:
```php
$query = $factory
    ->insert('friends', [
        'user_id' => 11,
        'friend_id' => 30,
    ])
    ->ignore(true);

$query->sql(); // INSERT IGNORE INTO `friends` (`user_id`, `friend_id`)"
$query->params(); // [11, 30]
```

Update on constraint violation:
```php
$query = $factory
    ->insert('friends', [
        'user_id' => 11,
        'friend_id' => 30,
        'name' => 'Charles'
    ])
    ->onDuplicateKeyUpdate([
        'user_id',
        'friend_id',
        'name' => 'Rick'
    ]);

$query->sql(); // INSERT INTO `friends` (`user_id`, `friend_id` ON DUPLICATE KEY UPDATE `user_id` = VALUES(`user_id`), `friend_id` = VALUES(`friend_id`), `name` = ?"
$query->params(); // [11, 30, 'Charles', 'Rick']
```

Postgres / SQLite:

Ignore on constraint violation:
```php
$query = $factory
    ->insert('friends', [
        'user_id' => 11,
        'friend_id' => 30,
    ])
    ->onConflictDoNothing(['friend_id']);

$query->sql(); // INSERT INTO "friends" ("user_id", "friend_id") ON CONFLICT ("friend_id") DO NOTHING"
$query->params(); // [11, 30]
```

Update on constraint violation:
```php
$query = $factory
    ->insert('friends', [
        'user_id' => 11,
        'friend_id' => 30,
        'name' => 'Charles'
    ])
    ->onConflictDoUpdate(
        ['friend_id'],
        [
            'user_id',
            'friend_id',
            'name' => 'Rick'
        ]
    );

$query->sql(); // INSERT INTO "friends" ("user_id", "friend_id" ON CONFLICT ("friend_id") DO UPDATE SET "user_id" = EXCLUDED."user_id", "friend_id" = EXCLUDED."friend_id", "name" = ?"
$query->params(); // [11, 30, 'Charles', 'Rick']
```

This also works for bulk inserts
