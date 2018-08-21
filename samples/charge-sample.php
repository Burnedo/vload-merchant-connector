<?php
require '../vendor/autoload.php';

$secret = 'vload-merchant-secret-key';
$config = \Vload\Common\Api\Config::init($secret); // this will provide the default config to connect to https://api.vload.expert/v1/
$connector = new \Vload\MerchantConnector\MerchantConnector($config);

// Creating address is optional.
// All parameters are optional and default to null.
// null parameters will not be sent to API.
$address = new \Vload\Common\VO\Address(
    'Warsaw', // city
    'PL', // contry two-letter ISO code
    'Ligustrowa 25F', // address line 1
    null, // address line 2
    'MZ', // state
    '09-999' // zip/postal
);

// While creating a customer you need to provide only the id parameter.
// Other parameters are optional and default to null.
// null parameters will not be sent to API.
$customer = new \Vload\Common\VO\Customer(
    1, // id
    'John', // first name
    'Doe', // last name
    'john@example.com', // email address
    '+48123123123', // phone number
    $address, // Address object
    '127.0.0.1' // IP address
);

$pin = '3156391887289068'; // Valid VLoad voucher pin
$orderId = 'SOMEORDER_33'; // Your order identifier
$description = 'Redeem #33'; // Your order description. Description is optional and empty by default.
try {
    /** @var \Vload\Common\VO\Charge $charge */
    $charge = $connector->charge($orderId, $pin, $customer, $description);
} catch (\Vload\Common\Exception\OperationFailed $e) {
    /*
     * Your error handling logic.
     */
}

// ... do something with the processed charge
/**
 * The ID of the charge.
 * This can be used to retrieve charge information later.
 * @see retrieve-charge-samples.php
 */
$chargeId = $charge->getId();
/**
 * Amount is a positive integer in the smallest currency unit.
 * (e.g., 100 cents to charge $1.00 or 100 to charge ¥100, a zero-decimal currency)
 */
$amount = $charge->getAmount();
/**
 * Three-letter ISO currency code (ISO-4217). Must be a supported currency.
 */
$currency = $charge->getCurrency();

