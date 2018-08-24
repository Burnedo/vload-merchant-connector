<?php
namespace Vload\MerchantConnectorTest\Mock;

class ChargeData
{
    const ID = 'AWT_fCCMh9GTrU2CKeaeXw==';
    const AMOUNT = 1000;
    const CURRENCY = 'USD';
    const DESCRIPTION = 'Test redeem';
    const VOUCHER = '01CKZQR849A9MM529MF5HCG8RN';
    const ORDER_ID = '1533294682';
    const STATUS = 'success';

    public static function mockChargeResponse()
    {
        return [
            'id' => self::ID,
            'amount' => self::AMOUNT,
            'currency' => self::CURRENCY,
            'customer' => [
                'id' => CustomerData::ID,
                'firstname' => CustomerData::FIRSTNAME,
                'lastname' => CustomerData::LASTNAME,
                'email' => CustomerData::EMAIL,
                'ip' => CustomerData::IP_ADDRESS,
                'address_city' => CustomerData::ADDRESS_CITY,
                'address_country' => CustomerData::ADDRESS_COUNTRY,
                'address_line1' => CustomerData::ADDRESS_LINE1,
                'address_line2' => CustomerData::ADDRESS_LINE2,
                'address_zip' => CustomerData::ADDRESS_ZIP,
                'address_state' => CustomerData::ADDRESS_STATE,
                'phone' => CustomerData::PHONE,
            ],
            'description' => self::DESCRIPTION,
            'voucher' => self::VOUCHER,
            'order_id' => self::ORDER_ID,
            'status' => self::STATUS,
        ];
    }
}
