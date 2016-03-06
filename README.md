# Serializer Driver for Eloquent ORM Models

[![Build Status](https://travis-ci.org/nilportugues/serializer-eloquent-driver.svg)](https://travis-ci.org/nilportugues/serializer-eloquent-driver)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nilportugues/serializer-eloquent-driver/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/nilportugues/serializer-eloquent-driver/?branch=master) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/497f9e58-e979-453e-bc04-b1501b88d027/mini.png?)](https://insight.sensiolabs.com/projects/497f9e58-e979-453e-bc04-b1501b88d027) 
[![Latest Stable Version](https://poser.pugx.org/nilportugues/serializer-eloquent/v/stable?)](https://packagist.org/packages/nilportugues/serializer-eloquent) 
[![Total Downloads](https://poser.pugx.org/nilportugues/serializer-eloquent/downloads?)](https://packagist.org/packages/nilportugues/serializer-eloquent) 
[![License](https://poser.pugx.org/nilportugues/serializer-eloquent/license?)](https://packagist.org/packages/nilportugues/serializer-eloquent) 
[![Donate](https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif)](https://paypal.me/nilportugues)

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


## Todo

Code all examples provided in the Eloquent documentation for relationships as tests:

- [X] One To One
- [X] One To Many
- [X] Has Many Through
- [ ] Polymorphic Relations
- [ ] Many To Many
- [ ] Many To Many Polymorphic Relations

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
