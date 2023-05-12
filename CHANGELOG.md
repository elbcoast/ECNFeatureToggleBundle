# Change Log

## 3.1.0 - 2023-05-12

### Added

- added support for PHP >=8
- added refactoring tools (php-cs-fixer, rector)

## 3.0.0 - 2022-02-01

### Added

- added support for PHP 8 and 8.1
- added support for Symfony 5.4 and 6
- added use of attributes

### Changed

- ControllerFilterEvent to ControllerEvent

### Removed

- removed support for PHP 7 and lower
- removed support for Symfony 3 and 4
- removed annotations

## 2.0.0 - 2019-12-25

### Added

- Symfony 4 support
- PHP 7 Coding Style

### Changed

- Removed Twig dependency

## 1.1.1 - 2016-06-03

### Fixed

- Updated composer dependency for Symfony 3 support

## 1.1.0 - 2016-03-25

### Added

- Added an AlwaysFalseVoter
- The default voter can now be overridden by configuration

## 1.0.1 - 2016-03-25

### Changed

- Removed dependency on Symfony\Component\HttpFoundation\ParameterBag
- RatioVoter now requires SessionInterface rather than Session in the constructor

## 1.0.0 - 2016-01-12

### Added

- Stickyness for ratio voter
- Symfony 2.8 and 3.0 support
- Twig tag
- Twig Integration tests
- Feature annotation for controllers


## 0.10.1 - 2015-10-20

### Fixed

- Schedule voter DateTime comparison


## 0.10.0 - 2015-10-19

### Added

- Schedule voter

### Changed

- Improved code


## 0.9.0 - 2015-08-02

- Initial release
