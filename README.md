# php-profitstars

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Total Downloads][ico-downloads]][link-downloads]

Jack Henry ProfitStars provides an API for handling ACH transactions. This package is a Laravel/Lumen wrapper
to access these transactions.

The package is currently not exhaustive in terms of what is available from the API; I have only implemented
parts of the API that are needed for my use.  That said, expanding this package should be fairly trivial
and if you should need additional pieces please feel free to modify and submit a pull request.

## Install

Via Composer

``` bash
$ composer require incraigulous/php-profitstars
```

## Usage

``` php
$credentials = [
    'store-id'=>"YOUR_STORE_ID",
    'store-key'=>"YOUR_STORE_KEY",
    'entity-id'=>"YOUR_ENTITY_ID",
    'location-id'=>"YOUR_LOCATION_ID",
];
$proc = new \incraigulous\ProfitStars\ProcessTransaction($credentials);
$trans = new \incraigulous\ProfitStars\WSTransaction;

// Test connection
if($proc->TestConnection()) {
	// Success
}

// Test credentials
if($proc->TestCredentials()) {
	// Success
}

// AuthorizeTransaction
$trans->RoutingNumber = 111000025;
$trans->AccountNumber = 5637492437;
$trans->TotalAmount = 9.95;
$trans->TransactionNumber = 12334;
$trans->NameOnAccount = 'Joe Smith';
$trans->EffectiveDate = '2015-11-04';
if($proc->AuthorizeTransaction($tras)) {
	// ReferenceNumber in $proc->ReferenceNumber	
} else {
	// Error message in $proc->ResponseMessage
}

// CaptureTransaction
$proc->ReferenceNumber = 'reference number';
if($proc->CaptureTransaction(9.95)) {
	// Success 
} else {
	// Error message in $proc->ResponseMessage
}

// VoidTransaction
$proc->ReferenceNumber = 'reference number';
if($proc->VoidTransaction()) {
	// Success;
} else {
	// Error message in $proc->ResponseMessage
}

// RefundTransaction
$proc->ReferenceNumber = 'reference number';
if($proc->RefundTransaction()) {
	// Success, refund info in $proc->ResponseMessage
} else {
	// Error message in $proc->ResponseMessage
}

```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.


## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Testing

PhpUnit can be run directly from the package folder. You will need to include a .env file to provide your sandbox connection credentials. An example .env file has been provided (.env.example).


## Security

If you discover any security related issues, please email me@jdavidbaker.com instead of using the issue tracker.

## Credits

This package was forked from @jdavidbakers's Laravel specific Profit Stars SDK.

- [J David Baker](https://github.com/jdavidbakr)
- [Incraigulous][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/incraigulous/php-profitstars.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/incraigulous/php-profitstars.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/incraigulous/php-profitstars
[link-downloads]: https://packagist.org/packages/incraigulous/php-profitstars
[link-author]: https://github.com/incraigulous
[link-contributors]: ../../contributors
