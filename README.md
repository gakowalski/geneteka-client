# Geneteka CLI Client

## Usage

Search for a person X Y born in year Z in mazowieckie region:

```bash
php -a
```

Then in PHP interactive shell:

```php
require 'functions.php';
var_dump(geneteka_search(['name' => 'X', surname' => 'Y', 'record_type' => 'birth', 'from' => 'Z', 'to' => 'Z', 'region' => 'mazowieckie']));
```

or for all regions

```php
require 'functions.php';
geneteka_find_person('X', 'Y', 'birth', options: [ 'from' => 'Z', 'to' => 'Z' ]);
```