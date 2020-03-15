# Change Log
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) 
and this project adheres to [Semantic Versioning](http://semver.org/).

## [4.1.0]

### Added

- Add SQLite engine to support boolean values (#98 by @roomcays)

## [4.0.0]

### Changed

- Require PHP >= 7.2 (#95)
- Renamed `fn` to `func` for PHP 7.4 compatibility (#95)

### Removed

- Removed support for criteria strings (#95)

## [3.3.1]

### Fixed

- Prevent escaping `*` when qualified (#93)

## [3.3.0]

### Changed

- Allow expressions to be used with `orderBy()` (#79 by @SamelVhatargh)

## [3.2.0]

### Added

- Allow clearing ordering with `orderBy(null)` (#77 by @felixpenrose)

## [3.1.1]

### Fixed

- `LIMIT` and `OFFSET` could not be reset (#66)

## [3.1.0]

### Added

- Allow `fn` to be used with parameters as well as identifiers (#57)
- Add `addColumns` and `addFrom` methods to append on `SELECT` queries (#58)

## [3.0.1]

### Fixed

- Boolean and null parameters should be output in SQL (#55)

## [3.0.0]

### Changed

- Completely new interface
- Improved handling of sub-queries and composition
- Functional and string based criteria builders
- Removal of all static methods
- Requires PHP 7.1 or better

## [2.3.1]

### Fixed

- Normalize `ValueList` parameters when passed an array

## [2.3.0]

### Added

- Add `Query` interface to differentiate between queries and statements
- Add `Alias` and `Reference` objects for additional flexibility

### Changed

- `SelectQuery` can now accept a query statement as a `join` table

## [2.2.0]

### Added

- `SelectQuery` can now remove `limit` and `orderBy` by setting `null` (@bpolaszek)

## [2.1.0]

### Added

- MySQL now supports `limit` and `orderBy` for update and delete queries (@gnoddep)

## [2.0.2]

### Fixed

- Expressions can now be used with `groupBy` (@kfreiman)

## [2.0.1]

### Fixed

- Qualified tables are now supported by `InsertQuery`

## [2.0.0]

### Changed

- Statements are now supported as parameters in conditions
- Multi-line inserts are now supported by `InsertQuery`

### Removed

- `InValue` has been removed in favor of `ValueList`
- `InsertMultipleQuery` has been removed

## [1.4.0]

### Added

- Multi-line insert statements are now supported by `InsertMultipleQuery`

## [1.3.0]

### Added

- `SELECT` queries now support `DISTINCT` (@luketlancaster)

## [1.2.1]

### Fixed

- Query factory no longer requires an explicit engine

## [1.2.0]

### Added

- Expressions can now be used with `ORDER BY` (@MelleB)

## [1.1.2]

### Fixed

- Replacement of boolean and null values would cause PDO to error on execute (@MelleB)

## [1.1.1]

### Fixed

- Compiling `INSERT` and `UPDATE` queries multiple times could produce different SQL

## [1.1.0]

### Added

- Expressions can now be used as values for `INSERT` and `UPDATE` queries

## [1.0.3]

### Fixed

- Invalid SQL would be produced by Postgres queries when no RETURNING values were defined

## [1.0.2]

### Fixed

- Multiple JOIN statements are now combined correctly (@kfreiman)

## [1.0.1]

### Fixed

- PHP 5.x was failing to assign query factory engine correctly

### Added

- PHP 5.x build scripts

## [1.0.0]

Initial release.
