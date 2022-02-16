# Env

A simple library to loads environment variables from “.env” file.

## Installation

Install via [Composer](https://getcomposer.org/):

```bash
$ composer require sergeyakovlev/env
```

## Usage

### Initialization

```php
use SergeYakovlev\Env\Env;

// If necessary non-default initialization
Env::init('/path/to/project', ['.env.dist', '.env']); // This is the default behavior
```

### Use cases

```php
// The first use case
$dbHostname = Env::var('DB_HOSTNAME');

// The second use case is with the default value
$dbHostname = Env::var('DB_HOSTNAME', 'localhost');

// Check if an environment variable is existed
$dbHostnameIsExists = Env::exists('DB_HOSTNAME');
```

### Example of “.env” file

```ini
# Comment Line

STRING_VAR1=StringValueWithoutSpaces
STRING_VAR2="String value with spaces"

BOOLEAN_VAR1=true
BOOLEAN_VAR2=false

BOOLEAN_VAR3=on
BOOLEAN_VAR4=off

INT_VAR=123

FLOAT_VAR=123.45
```
