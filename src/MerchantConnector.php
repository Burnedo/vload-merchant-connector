<?php
namespace Vload\MerchantConnector;

use Vload\Common\Api\Client;
use Vload\Common\Api\Config;
use Vload\Common\Api\VOConverter;
use Vload\Common\VO\Address;
use Vload\Common\VO\Charge;
use Vload\Common\VO\Customer;
use Vload\Common\VO\Voucher;
use Vload\MerchantConnector\Api\MerchantEndpoints;

class MerchantConnector
{
    /** @var Client */
    private $client;

    /**
     * @param Config $apiConfig
     */
    public function __construct($apiConfig)
    {
        if (!($apiConfig instanceof Config)) {
            throw new \InvalidArgumentException('apiConfig must be an instance of Vload\Common\Api\Config');
        }
        $this->client = $apiConfig->getClient();
    }

    /**
     * @param $pin
     * @return Voucher
     * @throws \Vload\Common\Exception\CommunicationFailed
     * @throws \Vload\Common\Exception\Conflict
     * @throws \Vload\Common\Exception\InvalidInput
     * @throws \Vload\Common\Exception\NotFound
     * @throws \Vload\Common\Exception\Unauthorized
     */
    public function validate($pin)
    {
        $params = [
            'pin' => $pin
        ];

        $response = $this->client->post(MerchantEndpoints::VOUCHER_VALIDATE, $params);

        $date = date('Y-m-d H:i P', $response['created']);
        $created = new \DateTimeImmutable($date);

        $voucher = new Voucher(
            $response['id'],
            null, // by design no pin in validate voucher response
            $response['value'],
            $response['currency'],
            $response['voucher_type'],
            $created
        );
        return $voucher;
    }

    /**
     * @param string $orderId
     * @param string $pin
     * @param Customer $customer
     * @param string $description
     * @return Charge
     * @throws \Vload\Common\Exception\CommunicationFailed
     * @throws \Vload\Common\Exception\Conflict
     * @throws \Vload\Common\Exception\InvalidInput
     * @throws \Vload\Common\Exception\NotFound
     * @throws \Vload\Common\Exception\Unauthorized
     */
    public function charge($orderId, $pin, $customer, $description = '')
    {
        $params = [
            'pin' => $pin,
            'order_id' => $orderId,
            'customer' => VOConverter::convertForRequest($customer),
            'description' => $description
        ];
        $response = $this->client->post(MerchantEndpoints::VOUCHER_CHARGE, $params);
        $charge = $this->createChargeFromResponse($response);
        return $charge;
    }

    /**
     * @param string $chargeId
     * @return Charge
     * @throws \Vload\Common\Exception\CommunicationFailed
     * @throws \Vload\Common\Exception\Conflict
     * @throws \Vload\Common\Exception\InvalidInput
     * @throws \Vload\Common\Exception\NotFound
     * @throws \Vload\Common\Exception\Unauthorized
     */
    public function retrieveCharge($chargeId)
    {
        $endpoint = sprintf(MerchantEndpoints::VOUCHER_CHARGE_RETRIEVE, $chargeId);
        $response = $this->client->get($endpoint);
        $charge = $this->createChargeFromResponse($response);
        return $charge;
    }

    /**
     * @param array $response
     * @return Charge
     */
    private function createChargeFromResponse($response)
    {
        $address = new Address(
            $response['customer']['address_city'],
            $response['customer']['address_country'],
            $response['customer']['address_line1'],
            $response['customer']['address_line2'],
            $response['customer']['address_state'],
            $response['customer']['address_zip']
        );
        $customer = new Customer(
            $response['customer']['id'],
            $response['customer']['firstname'],
            $response['customer']['lastname'],
            $response['customer']['email'],
            $response['customer']['phone'],
            $address,
            $response['customer']['ip_address']
        );
        $charge = new Charge(
            $response['id'],
            $response['amount'],
            $response['currency'],
            $response['description'],
            $customer,
            $response['voucher'],
            $response['order_id'],
            $response['status']
        );
        return $charge;
    }
}
