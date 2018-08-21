<?php
require '../vendor/autoload.php';

$secret = 'vload-merchant-secret-key';
$config = \Vload\Common\Api\Config::init($secret); // this will provide the default config to connect to https://api.vload.expert/v1/
$connector = new \Vload\MerchantConnector\MerchantConnector($config);

$pin = '3156391887289068'; // Valid VLoad voucher pin
try {
    /** @var \Vload\Common\VO\Voucher $charge */
    $voucher = $connector->validate($pin);
} catch (\Vload\Common\Exception\OperationFailed $e) {
    /*
     * Your error handling logic.
     */
}

// ... do something with the retrieved voucher object
/**
 * Value is a positive integer in the smallest currency unit.
 * (e.g., 100 cents to charge $1.00 or 100 to charge ¥100, a zero-decimal currency)
 */
$amount = $voucher->getValue();
/**
 * Three-letter ISO currency code (ISO-4217). Must be a supported currency.
 */
$currency = $voucher->getCurrency();
/**
 * Voucher denomination.
 */
$type = $voucher->getType();
/**
 * DateTimeImmutable time of voucher creation
 */
$created = $voucher->getCreated();