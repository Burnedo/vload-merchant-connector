<?php
namespace Vload\MerchantConnectorTest;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Vload\Common\Api\Client;
use Vload\Common\Api\Config;
use Vload\Common\VO\Address;
use Vload\Common\VO\Charge;
use Vload\Common\VO\Customer;
use Vload\Common\VO\Voucher;
use Vload\MerchantConnector\MerchantConnector;
use Vload\MerchantConnectorTest\Mock\ChargeData;
use Vload\MerchantConnectorTest\Mock\CustomerData;
use Vload\MerchantConnectorTest\Mock\VoucherData;

class MerchantConnectorTest extends TestCase
{

    /** @var Client|MockObject */
    private $client;

    public function setUp()
    {
        $this->client = $this->getMockBuilder(Client::class)->disableOriginalConstructor()->getMock();
    }

    private function createInstance()
    {
        /** @var Config|MockObject $config */
        $config = $this->getMockBuilder(Config::class)->disableOriginalConstructor()->getMock();
        $config->method('getClient')->willReturn($this->client);
        return new MerchantConnector($config);
    }

    public function testConstructorValidation()
    {
        $this->expectException(\InvalidArgumentException::class);
        new MerchantConnector(['invalid' => 'config']);
    }

    /**
     * @throws \Vload\Common\Exception\CommunicationFailed
     * @throws \Vload\Common\Exception\Conflict
     * @throws \Vload\Common\Exception\InvalidInput
     * @throws \Vload\Common\Exception\NotFound
     * @throws \Vload\Common\Exception\Unauthorized
     */
    public function testValidate()
    {
        $this->client
            ->method('post')
            ->willReturn(VoucherData::mockVoucherResponse());
        $connector = $this->createInstance();
        $voucher = $connector->validate(VoucherData::PIN);
        $this->assertInstanceOf(Voucher::class, $voucher);
        $this->assertEquals(VoucherData::ID, $voucher->getId());
        $this->assertEquals(VoucherData::CURRENCY, $voucher->getCurrency());
        $this->assertNull($voucher->getPin());
        $this->assertEquals(VoucherData::VALUE, $voucher->getValue());
        $this->assertEquals(VoucherData::VOUCHER_TYPE, $voucher->getType());
        $this->assertInstanceOf(\DateTimeImmutable::class, $voucher->getCreated());
    }

    /**
     * @throws \Vload\Common\Exception\CommunicationFailed
     * @throws \Vload\Common\Exception\Conflict
     * @throws \Vload\Common\Exception\InvalidInput
     * @throws \Vload\Common\Exception\NotFound
     * @throws \Vload\Common\Exception\Unauthorized
     */
    public function testCharge()
    {
        $this->client
            ->method('post')
            ->willReturn(ChargeData::mockChargeResponse());
        $customer = CustomerData::mockCustomer();

        $connector = $this->createInstance();
        /** @var Charge $charge */
        $charge = $connector->charge(
            ChargeData::ORDER_ID,
            VoucherData::PIN,
            $customer,
            ChargeData::DESCRIPTION
        );
        $this->assertValidCharge($charge, $customer);
    }

    /**
     * @throws \Vload\Common\Exception\CommunicationFailed
     * @throws \Vload\Common\Exception\Conflict
     * @throws \Vload\Common\Exception\InvalidInput
     * @throws \Vload\Common\Exception\NotFound
     * @throws \Vload\Common\Exception\Unauthorized
     */
    public function testRetrieveCharge()
    {
        $this->client
            ->method('get')
            ->willReturn(ChargeData::mockChargeResponse());
        $customer = CustomerData::mockCustomer();
        $connector = $this->createInstance();
        $charge = $connector->retrieveCharge(ChargeData::ID);
        $this->assertValidCharge($charge, $customer);
    }

    /**
     * @param Charge $charge
     * @param Customer $customer
     */
    private function assertValidCharge($charge, $customer)
    {
        $this->assertInstanceOf(Charge::class, $charge);
        $this->assertEquals(ChargeData::ID, $charge->getId());
        $this->assertEquals(ChargeData::AMOUNT, $charge->getAmount());
        $this->assertEquals(ChargeData::CURRENCY, $charge->getCurrency());
        $this->assertEquals($customer, $charge->getCustomer());
        $this->assertEquals(ChargeData::DESCRIPTION, $charge->getDescription());
        $this->assertEquals(ChargeData::VOUCHER, $charge->getVoucher());
        $this->assertEquals(ChargeData::ORDER_ID, $charge->getOrderId());
        $this->assertEquals(ChargeData::STATUS, $charge->getStatus());
    }
}
