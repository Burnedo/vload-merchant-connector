<?php
require '../vendor/autoload.php';

$secret = 'vload-merchant-secret-key';
$config = \Vload\Common\Api\Config::init($secret); // this will provide the default config to connect to https://api.vload.expert/v1/
$connector = new \Vload\MerchantConnector\MerchantConnector($config);

/**
 * The ID of the charge to be retrieved.
 * This can be obtained by creating a charge.
 * @see charge-sample.php
 */
$chargeId = 'someChargeId...';
try {
    /** @var \Vload\Common\VO\Charge $charge */
    $charge = $connector->retrieveCharge($chargeId);
} catch (\Vload\Common\Exception\OperationFailed $e) {
    /*
     * Your error handling logic.
     */
}

// ... do something with the retrieved charge
/**
 * Amount is a positive integer in the smallest currency unit.
 * (e.g., 100 cents to charge $1.00 or 100 to charge ¥100, a zero-decimal currency)
 */
$amount = $charge->getAmount();
/**
 * Three-letter ISO currency code (ISO-4217). Must be a supported currency.
 */
$currency = $charge->getCurrency();

