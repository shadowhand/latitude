---
layout: default
title: String Criteria
---

Latitude leverages [hoa/ruler][hoa-ruler] to enable creating criteria from string
expressions, which  will be converted into a `CriteriaInterface` that can be used
for any query that supports criteria.

[hoa-ruler]: https://hoa-project.net/En/Literature/Hack/Ruler.html

```php
$criteria = $factory->criteria('id = 5');
// is the same as ...
$criteria = field('id')->eq(5);
```

_**Note:** To use this functionality `hoa/ruler` must be installed!_

## Why?

The benefit of criteria expressions is that they can be used in domain code
without importing any references to Latitude:

```php
namespace Acme\Domain\Person;

class PersonQuery
{
    /** @var int */
    private $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function getCriteria(): string
    {
        return "person.id = {$this->id}";
    }
}
```

This maintains a proper separation of concerns, where the domain is totally
unaware of the underlying storage engine. The same criteria can be used to
generate a SQL query or to filter an in-memory collection, either with `hoa/ruler`
or [rulerz][rulerz].

[rulerz]: https://github.com/K-Phoen/rulerz

**[Back](../)**
