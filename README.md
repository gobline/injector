# Injector Component - Mendo Framework

The Mendo Injector component allows you to inject dependencies with [Traits](http://php.net/manual/en/language.oop5.traits.php).

## Usage

```php
$injector = (new Mendo\Injector\TraitDependencyInjector())
    ->register("Dependency\\ServiceATrait", function ($o) use ($dic) { $o->setServiceA($dic['service.a']); });
    ->register("Dependency\\ServiceBTrait", function ($o) use ($dic) { $o->setServiceB($dic['service.b']); });

$injector->inject($consumer);
```

In the example above, if the consumer uses the ```Dependency\\ServiceATrait``` trait, the injector will then inject the matching service through the setter ```setServiceA()``` defined in the trait.

In order to do so, the injector makes use of [reflection](http://php.net/manual/en/class.reflectionclass.php) to find the traits that the consumer uses.

## Installation

You can install Mendo Injector using the dependency management tool [Composer](https://getcomposer.org/).
Run the *require* command to resolve and download the dependencies:

```
composer require mendoframework/injector
```