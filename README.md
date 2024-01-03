# Laravel Enum Generator

Laravel Enum Generator as name suggests helps generate native php enum objects to facilitate laravel enum usage using artisan command.

## Requirement

Laravel 10
php > 8.1

## Installation

```bash
composer require archvayu/laravel-enum
```

## Usage

There are basically two types of enums that can be used: [pure](https://www.php.net/manual/en/language.enumerations.basics.php) and [backed](https://www.php.net/manual/en/language.enumerations.backed.php).

To generate pure enum:

```bash
php artisan make:enum EnumName --tyep=pure
```

To generate backed enum with integer scalar equivalent:

```bash
php artisan make:enum BackedEnum --type=backed --scalar=int
```

To generate backed enum with string scalar equivalent:

```bash
php artisan make:enum BackedEnum --type=backed --scalar=string
```

Backed Enum contains two methods to get vlaues array of scalar and array for select operations.

## LICENSE

Licensed using [MIT License](./LICENSE)
