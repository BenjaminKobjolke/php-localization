# PHP Localization

[![License: MIT](https://img.shields.io/badge/License-MIT-green.svg)](https://opensource.org/licenses/MIT)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/dvlpr1996/php-localization?style=flat)](https://packagist.org/packages/dvlpr1996/php-localization)
[![Total Downloads](https://img.shields.io/packagist/dt/dvlpr1996/php-localization)](https://packagist.org/packages/dvlpr1996/php-localization)

Php Localization Library

## Requirements

- PHP 8 or higher

## Install

You can install the package via composer:

```bash
composer require dvlpr1996/php-localization
```

## Features

Localization With 2 Drivers

- Array
- Json

### Default Language Directory

You can configure a `defaultLangDir` to provide base/default translations that get automatically merged with language-specific translations. Language-specific translations override the defaults.

```php
$config = [
    'driver' => 'array',
    'langDir' => '/path/to/lang/',
    'defaultLangDir' => '/path/to/default/lang/', // Optional: base translations
    'defaultLang' => 'en',
    'fallBackLang' => null
];

$localization = new \PhpLocalization\Localization($config);
```

## Documentation

See the [documentation](https://github.com/dvlpr1996/php-localization/wiki) for detailed installation and usage instructions.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## issues

If you discover any issues, please using the issue tracker.

## Credits

- [Nima jahan bakhshian](https://github.com/dvlpr1996)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
