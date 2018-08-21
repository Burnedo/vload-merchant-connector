<?php
namespace Vload\MerchantConnectorTest\Mock;

use Vload\Common\VO\Address;
use Vload\Common\VO\Customer;

class CustomerData
{
    const ID = 1;
    const FIRSTNAME = 'John';
    const LASTNAME = 'Doe';
    const EMAIL = 'john@example.com';
    const IP_ADDRESS = '127.0.0.1';
    const ADDRESS_CITY = 'Warsaw';
    const ADDRESS_COUNTRY = 'PL';
    const ADDRESS_LINE1 = 'Ligustrowa 25F';
    const ADDRESS_LINE2 = '';
    const ADDRESS_ZIP = '09-999';
    const ADDRESS_STATE = 'MZ';
    const PHONE = '+48123123123';

    public static function mockCustomer()
    {
        $address = new Address(
            self::ADDRESS_CITY,
            self::ADDRESS_COUNTRY,
            self::ADDRESS_LINE1,
            self::ADDRESS_LINE2,
            self::ADDRESS_STATE,
            self::ADDRESS_ZIP
        );
        $customer = new Customer(
            self::ID,
            self::FIRSTNAME,
            self::LASTNAME,
            self::EMAIL,
            self::PHONE,
            $address,
            self::IP_ADDRESS
        );
        return $customer;
    }
}
