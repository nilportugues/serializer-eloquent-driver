# Serializer Driver for Eloquent ORM Models

## What?
This is a driver for the [Serializer](https://github.com/nilportugues/serializer) library caring of Eloquent ORM model serialization.

## Why?
Due to the popularity of Eloquent, specially in Laravel apps, a driver has been develop to unite all Eloquent serialization edge-cases for [Serializer](https://github.com/nilportugues/serializer) in a single library.


## Installation

Use [Composer](https://getcomposer.org) to install the package:

```json
$ composer require nilportugues/serializer-eloquent
```

## Usage

This will return an array following the [Serializer](https://github.com/nilportugues/serializer) format.

```php
use NilPortugues\Serializer\Drivers\Eloquent\EloquentDriver;

$serialized = EloquentDriver::serialize($value);
```


## Contribute

Contributions to the package are always welcome!

* Report any bugs or issues you find on the [issue tracker](https://github.com/nilportugues/serializer-eloquent-driver/issues/new).
* You can grab the source code at the package's [Git repository](https://github.com/nilportugues/serializer-eloquent-driver).


## Support

Get in touch with me using one of the following means:

 - Emailing me at <contact@nilportugues.com>
 - Opening an [Issue](https://github.com/nilportugues/serializer-eloquent-driver/issues/new)



## Authors

* [Nil Portugués Calderó](http://nilportugues.com)
* [The Community Contributors](https://github.com/nilportugues/serializer-eloquent-driver/graphs/contributors)


## License
The code base is licensed under the [MIT license](LICENSE).
