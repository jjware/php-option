# Option
Option sum type for PHP

## Getting Started
```
composer require jjware/option
```
## Creation
The `Option` class resides in namespace `JJWare\Utils\Option`

You can create an `Option` simply by calling the static `some` method:
```php
$opt = Option::some('example value');
```
If you have a variable that may contain a `null` value, you may use the `nullable` static method:
```php
$opt = Option::nullable($value);
```
If you have a case where you need to return an empty value, you may use the `none` static method:
```php
$opt = Option::none();
```
## Usage
Once you have an `Option`, there are many operations you can perform against it.

Let's say we have a function that may or may not return a value:
```php
function getSetting(string $setting) : Option
{
    // Try to find the setting if it exists...
    return Option::nullable($result);
}
```
You may provide a default value in the case that your `Option` is empty:
```php
$port = getSetting('port')->getOrElse(8080);
```
If your default value requires expensive calculation or calls to external resources, you may only want to get the default value when necessary:
```php
$port = getSetting('port')->getOrElseGet(function () use ($db) {
    return $db->getDefaultPortFromDatabase();
});

// or using an instance method reference

$port = getSetting('port')->getOrElseGet([$db, 'getDefaultPortFromDatabase']);
```
The absence of a value may be an exceptional case for you:
```php
$port = getSetting('port')->getOrThrow(function () {
   return new UnderflowException("setting does not exist");
});
```
You may need to change the value within the `Option` in some way if it exists:
```php
$port = getSetting('port')->map(function ($x) {
   return intval($x);
})->getOrElse(8080);

// or using a function reference

$port = getSetting('port')->map('intval')->getOrElse(8080);
```
You may have a need to map to an entirely different `Option`:
```php
$scheme = getSetting('port')->flatMap(function ($x) {
   return getSchemeForPort($x);
})->getOrElse('http');

// or as a function reference

$scheme = getSetting('port')->flatMap('getSchemeForPort')->getOrElse('http');
```
You may not want the value unless it meets specific criteria:
```php
$port = getSetting('port')->filter(function ($x) {
    return $x >= 1024 && $x <= 49151;
})->getOrElse(8080);

// or using a static method reference

$port = getSetting('port')->filter('Filters::registeredPort')->getOrElse(8080);
```
Let's say you have a need to test for the presence of a value:
```php
$port = getSetting('port');

if (!$port->isSome()) {
    $log->debug("port setting empty");
    throw new InvalidArgumentException("port not provided");
}
```
