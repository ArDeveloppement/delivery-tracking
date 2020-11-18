# Delivery Tracking

[![Software License][ico-license]](LICENSE.md)

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/630d6d84-14d4-439d-ba30-0cb0b84f8d01/big.png)](https://insight.sensiolabs.com/projects/630d6d84-14d4-439d-ba30-0cb0b84f8d01)

This is a framework agnostic delivery tracking library for PHP 5.4+.
It uses the Adapter design pattern to provide a unified api over delivery services, and a common list of delivery statuses.
This library respects PSR-1, PSR-2, and PSR-4. 

## Install

Via Composer

``` bash
$ composer require cospirit/delivery-tracking
```

## Usage

``` php
$chronopostAdapter = new ChronopostAdapter();
$deliveryTracking = new DeliveryTracking($chronopostAdapter);

$status = $deliveryTracking->getDeliveryStatus('tracking-number');
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any **security related** issues, please use the issue tracker.

## Credits

- [Laurent Wiesel][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square

[link-author]: https://github.com/lwiesel
[link-contributors]: ../../contributors
