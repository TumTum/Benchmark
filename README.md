# Testing

[![Latest Stable Version](https://poser.pugx.org/ucsdmath/Testing/v/stable)](https://packagist.org/packages/ucsdmath/Testing)
[![License](https://poser.pugx.org/ucsdmath/Testing/license)](https://packagist.org/packages/ucsdmath/Testing)
[![Total Downloads](https://poser.pugx.org/ucsdmath/Testing/downloads)](https://packagist.org/packages/ucsdmath/Testing)
[![Latest Unstable Version](https://poser.pugx.org/ucsdmath/Testing/v/unstable)](https://packagist.org/packages/ucsdmath/Testing)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ucsdmath/Testing/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/ucsdmath/Testing/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/ucsdmath/Testing/badges/build.png?b=master)](https://scrutinizer-ci.com/g/ucsdmath/Testing/build-status/master)

Testing is a testing and development library only. This is not to be used in a production.

## Installation using [Composer](http://getcomposer.org/)
You can install the class ```Testing``` with Composer and Packagist by
adding the ucsdmath/testing package to your composer.json file:

```
"require": {
    "php": ">=7.0.0",
    "ucsdmath/testing": "dev-master"
},
```
Or you can add the class directly from the terminal prompt:

```bash
$ composer require ucsdmath/testing
```

## Usage

``` php
$benchmark = new \UCSDMath\Testing\Benchmark();
```

## Documentation

No documentation site available at this time.
<!-- [Check out the documentation](http://math.ucsd.edu/~deisner/documentation/Testing/) -->

## Testing

``` bash
$ phpunit
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email deisner@ucsd.edu instead of using the issue tracker.

## Credits

- [Daryl Eisner](https://github.com/UCSDMath)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
