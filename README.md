# PHP Localization

[![License: MIT](https://img.shields.io/badge/License-MIT-green.svg)](https://opensource.org/licenses/MIT)

PHP Localization Library with support for JSON and Array drivers, dot notation for nested keys, and fallback languages.

## Requirements

- PHP 8.0 or higher

## Installation

### From GitHub

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
        "xida/php-localization": "*"
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
            "url": "D:/GIT/BenjaminKobjolke/php-localization"
        }
    ],
    "require": {
        "xida/php-localization": "*"
    }
}
```

**Note:** Use `*` for the version constraint with both VCS and path repositories.

## Quick Start

### 1. Create the language file

Create a `lang` directory with a JSON file per language:

```
project/
├── lang/
│   ├── en.json    # English translations
│   └── de.json    # German translations
└── public/
    └── index.php
```

**Important:** For JSON driver, use a single file per language named `{lang}.json` (e.g., `en.json`), NOT a folder structure like `lang/en/site.json`.

**lang/en.json:**
```json
{
    "site": {
        "title": "My Website",
        "description": "Welcome to my website"
    },
    "nav": {
        "home": "Home",
        "about": "About",
        "contact": "Contact"
    },
    "messages": {
        "welcome": "Hello, :name!",
        "errors": {
            "not_found": "Page not found",
            "server": "Server error"
        }
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
    'langDir' => __DIR__ . '/../lang/',  // Note: trailing slash required!
    'defaultLang' => 'en',
    'fallBackLang' => 'en'
]);
```

### 3. Get translations

```php
// Simple nested value
echo $localization->lang('site.title');
// Output: "My Website"

// Deeply nested value
echo $localization->lang('messages.errors.not_found');
// Output: "Page not found"

// With placeholder replacement
echo $localization->lang('messages.welcome', [':name' => 'John']);
// Output: "Hello, John!"
```

## Dot Notation

Access nested values in your JSON using dot notation:

| Key | Returns |
|-----|---------|
| `site.title` | `"My Website"` |
| `nav.home` | `"Home"` |
| `messages.errors.not_found` | `"Page not found"` |
| `messages.errors.server` | `"Server error"` |

## Configuration Options

| Option | Type | Required | Description |
|--------|------|----------|-------------|
| `driver` | string | Yes | `'json'` or `'array'` |
| `langDir` | string | Yes | Path to language directory **with trailing slash** |
| `defaultLang` | string | Yes | Default language code (e.g., `'en'`) |
| `fallBackLang` | string\|null | No | Fallback language if translation not found |
| `defaultLangDir` | string\|null | No | Optional base translations directory for merging |

### Example Configuration

```php
$localization = new Localization([
    'driver' => 'json',
    'langDir' => __DIR__ . '/../lang/',
    'defaultLang' => 'en',
    'fallBackLang' => 'en'
]);
```

## Directory Structure

### JSON Driver (Recommended)

Single file per language:

```
lang/
├── en.json     # {"site": {"title": "..."}, "nav": {...}}
├── de.json     # {"site": {"title": "..."}, "nav": {...}}
└── fr.json     # {"site": {"title": "..."}, "nav": {...}}
```

### Array Driver

Multiple PHP files per language:

```
lang/
├── en/
│   ├── site.php      # <?php return ['title' => 'My Site'];
│   └── nav.php       # <?php return ['home' => 'Home'];
└── de/
    ├── site.php
    └── nav.php
```

For array driver, use: `lang('site.title')` where `site` is the filename.

## String Replacement

Replace placeholders in translations:

**JSON:**
```json
{
    "greeting": "Hello, :name!",
    "items": "You have :count items"
}
```

**PHP:**
```php
echo $localization->lang('greeting', [':name' => 'John']);
// Output: "Hello, John!"

echo $localization->lang('items', [':count' => '5']);
// Output: "You have 5 items"
```

## Using with Twig

```php
<?php
require_once 'vendor/autoload.php';

use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use Twig\TwigFunction;
use PhpLocalization\Localization;

// Setup localization
$localization = new Localization([
    'driver' => 'json',
    'langDir' => __DIR__ . '/../lang/',
    'defaultLang' => 'en',
    'fallBackLang' => 'en'
]);

// Setup Twig
$loader = new FilesystemLoader(__DIR__ . '/../templates');
$twig = new Environment($loader);

// Add translation function
$twig->addFunction(new TwigFunction('t', function (string $key) use ($localization) {
    return $localization->lang($key);
}));

echo $twig->render('index.twig');
```

**In Twig templates:**
```twig
<title>{{ t('site.title') }}</title>
<nav>
    <a href="/">{{ t('nav.home') }}</a>
    <a href="/about">{{ t('nav.about') }}</a>
</nav>
<p>{{ t('messages.welcome', {':name': 'John'}) }}</p>
```

## Troubleshooting

### Empty string returned

1. **Check file path:** Ensure `langDir` has a trailing slash
2. **Check file exists:** The file should be `lang/en.json`, not `lang/en/en.json`
3. **Check JSON validity:** Use `json_decode()` to verify your JSON is valid
4. **Check key exists:** Verify the dot notation path matches your JSON structure

### File not found error

1. Ensure the language file exists: `lang/{defaultLang}.json`
2. Check that `langDir` points to the correct directory
3. For JSON driver, do NOT create a subdirectory (use `lang/en.json`, not `lang/en/`)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Credits

- Original: [Nima jahan bakhshian](https://github.com/dvlpr1996)
- Fork: [Benjamin Kobjolke](https://github.com/BenjaminKobjolke)
