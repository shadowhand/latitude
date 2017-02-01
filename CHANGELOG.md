# Change Log
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) 
and this project adheres to [Semantic Versioning](http://semver.org/).

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
