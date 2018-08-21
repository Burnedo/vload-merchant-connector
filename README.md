# VLoad API connector for merchants
## Installation
```bash
composer require vload/merchant-connector
```
## Usage
```php
MerchantConnector {
    public __construct(\Vload\Common\Api\Config $config)
    public validate(string $pin)
    public charge(string $orderId, string $pin, \Vload\MerchantConnector\VO\Customer $customer [, string $description)
}
```
### Create connector instance
##### Vanilla PHP
```php
$secret = 'vload_secret_key'; //Merchant secret key provided by VLoad
$config = \Vload\Common\Api\Config::init($secret);
$connector = new MerchantConnector($config);
```
##### Symfony service conatiner
```yaml
# config/services.yaml
services:
    # ...
    vload.config:
        class: Vload\Common\Api\Config
        factory: ['Vload\Common\Api\Config', init]
        arguments: ['Your-VLoad-secret']

    Vload\MerchantConnector\MerchantConnector:
        class: Vload\MerchantConnector\MerchantConnector
        arguments: ["@vload.config"]
```
### Validate voucher
```php
$pin = '3156391887289068';
$voucher = $connector->validate($pin);
```
#### Parameters
##### pin (required)
Vocucher pin
#### Returns
Method returns \Vload\Common\VO\Voucher object on success or throws [an exception](#exceptions) on failure.
### Charge voucher
```php
$orderId = '12345678';
$pin = '3156391887289068';
$customer = new \Vload\Common\VO\Customer(...);
$description = 'Test redeem';
$charge = $connetor->charge($orderId, $pin, $customer, $description);
```
#### Parameters
##### orderId (required)
Order reference in your system
##### pin (required)
VLoad voucher's pin number
##### customer (required)
Customer object in you system. Check [samples](/samples) for proper customer creation code.
##### description (optional)
Description of the charge
#### Returns
Method returns \Vload\Common\VO\Charge object on success or throws [an exception](#exceptions) on failure.
### Retrieve charge
```php
$chargeId = 'AWT_fCCMh9GTrU2CKeaeXw==';
$charge = $connector->retrieveCharge($chargeId);
```
#### Parameters
##### chargeId (required)
The ID of the charge to be retrieved, as returned from [charge voucher](#charge-voucher)
#### Returns
Method returns \Vload\Common\VO\Charge object on success or throws [an exception](#exceptions) on failure.
## Exceptions
Connector methods will throw following exceptions from \Vload\Common\Exception namespace if requested operations does not succeed at the VLoad API.
#### InvalidInput
The request was unacceptable, often due to missing a required parameter.
#### NotFound
Object you requested does not exist (e.g. invalid or inactive voucher)
#### Unauthorized
No valid API key provided.
#### Conflict
The request conflicts with another request (perhaps due to using the same idempotent key).
#### CommunicationFailed
Some internal API error occured.
## Samples
See [samples directory](/samples) for more information.

