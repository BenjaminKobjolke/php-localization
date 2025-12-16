# PHP Localization

[![License: MIT](https://img.shields.io/badge/License-MIT-green.svg)](https://opensource.org/licenses/MIT)

PHP Localization Library with support for JSON and Array drivers, dot notation for nested keys, and fallback languages.

## Requirements

- PHP 8.0 or higher

## Installation

### From GitHub (recommended for this fork)

Add the repository to your `composer.json`:

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/BenjaminKobjolke/php-localization.git"
        }
    ],
    "require": {
        "xida/php-localization": "dev-main"
    }
}
```

Then run:

```bash
composer install
```

### From local path (for development)

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "/path/to/php-localization"
        }
    ],
    "require": {
        "xida/php-localization": "dev-main"
    }
}
```

## Quick Start

### 1. Create language files

Create a `lang` directory with language subdirectories:

```
lang/
└── en/
    └── site.json
```

**lang/en/site.json:**
```json
{
    "title": "My Website",
    "meta": {
        "description": "Welcome to my website",
        "keywords": "web, site, example"
    }
}
```

### 2. Initialize the library

```php
<?php
require_once 'vendor/autoload.php';

use PhpLocalization\Localization;

$localization = new Localization([
    'driver' => 'json',
    'langDir' => __DIR__ . '/lang/',
    'defaultLang' => 'en',
    'fallBackLang' => 'en'
]);
```

### 3. Get translations

```php
// Get a simple value
echo $localization->lang('site.title');
// Output: "My Website"

// Get nested values using dot notation
echo $localization->lang('site.meta.description');
// Output: "Welcome to my website"

// Get entire file as array
$siteData = $localization->lang('site');
// Returns: ['title' => 'My Website', 'meta' => [...]]
```

## Dot Notation

Access nested values in your JSON files using dot notation:

**Format:** `filename.key.subkey.subsubkey`

**Example JSON (lang/en/messages.json):**
```json
{
    "welcome": "Welcome!",
    "errors": {
        "not_found": "Page not found",
        "server": {
            "500": "Internal server error",
            "503": "Service unavailable"
        }
    }
}
```

**Usage:**
```php
$localization->lang('messages.welcome');           // "Welcome!"
$localization->lang('messages.errors.not_found');  // "Page not found"
$localization->lang('messages.errors.server.500'); // "Internal server error"
```

## Configuration Options

| Option | Type | Description |
|--------|------|-------------|
| `driver` | string | `'json'` or `'array'` |
| `langDir` | string | Path to language directory (with trailing slash) |
| `defaultLang` | string | Default language code (e.g., `'en'`) |
| `fallBackLang` | string\|null | Fallback language if key not found |
| `defaultLangDir` | string\|null | Optional base translations directory |

## Directory Structure

### JSON Driver

```
lang/
├── en/
│   ├── site.json
│   ├── messages.json
│   └── errors.json
└── de/
    ├── site.json
    ├── messages.json
    └── errors.json
```

### Array Driver

```
lang/
├── en/
│   ├── site.php      # return ['title' => 'My Site'];
│   └── messages.php
└── de/
    ├── site.php
    └── messages.php
```

## String Replacement

Replace placeholders in translations:

**JSON:**
```json
{
    "greeting": "Hello, :name!"
}
```

**PHP:**
```php
echo $localization->lang('messages.greeting', [':name' => 'John']);
// Output: "Hello, John!"
```

## Using with Twig

```php
use Twig\TwigFunction;

$twig->addFunction(new TwigFunction('t', function (string $key) use ($localization) {
    return $localization->lang($key);
}));
```

**In templates:**
```twig
<title>{{ t('site.title') }}</title>
<p>{{ t('site.meta.description') }}</p>
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Credits

- Original: [Nima jahan bakhshian](https://github.com/dvlpr1996)
- Fork: [Benjamin Kobjolke](https://github.com/BenjaminKobjolke)
