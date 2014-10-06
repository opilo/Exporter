# Eloquent Exporter
An easy to use tool to export an eloquent query results to CSV format.

## Installation
Add eloquent-exporter to your `composer.json` file:
```JSON
"require": {
  "opilo/eloquent-exporter": "~1.0"
}
```
Use composer to install this package.
```bash
$ composer update
```

## Registering the Package
Register the service provider within the providers array found in `app/config/app.php`:
```php
'providers' => array(
    'Opilo\Exporter\ExporterServiceProvider'
)
```
Add an alias within the aliases array found in `app/config/app.php`:
```php
'aliases' => array(
    'OAuth' => 'Opilo\Exporter\Facades\Exporter',
)
```

## Configuration
Next step is to publish package configuration by running:
```bash
$ php artisan config:publish opilo/eloquent-exporter
```
Config file could be found in `app/config/packages/opilo/eloquent-exporter/config.php`

## Usage
You can use eloquent-exporter in two way

### Using Facade
Make sure to register exporter service manager and add exporter facades to your aliases, then you could do:
```php
<?php

// create your queryBuilder
$query = User::where('status', 1);

Exporter::export($query);
```

### Using IoC
Make sure exporter service manager is registered in your app config.
```php
<?php

// create your queryBuilder
$query = User::where('status', 1);

// create get exporter out of IoC
$exporter = App::make('exporter');

$exporter->export($query);
```

## Advanced Usages

### Filtering columns
You can set and filter columns you want to be exported, by passing an array of columns as second parameter to export method.
```php
<?php

// create your queryBuilder
$query = User::where('status', 1);

Exporter::export($query, ['username', 'email', 'name']);
```

### Relational Queries
When you want to export a joint query you should pass a key value array as third parameter of export method.
The **key** should be name of relation, and **value** is the column you want to export from related table.
```php
<?php

// create your queryBuilder
$query = User::where('status', 1)->with('roles');

Exporter::export($query, ['username', 'email', 'name'], ['roles' => 'title']);
```
The relation fields will join together by what is set in package config as `relation_glue` *(it is set to `|` by default)*

### Config file

field | description | default value
---|---|---
chunk_size | Size of chunk of results to be written in file. | 100
store_path | Where exported file should be stored. | app/storage/export/
file_extension | Extension of exported file | .ext
relation_glue | Character to join relation fields together | \| *(pipeline)*
csv_delimiter | Character to delimit columns in csv file | ,
