<?php
namespace Vload\MerchantConnectorTest\Mock;

class VoucherData
{
    const ID = '01CKR06E38KNVX4NGCFMDQT30C';
    const PIN = '8108311059995025';
    const VALUE = 1000;
    const CURRENCY = 'USD';
    const VOUCHER_TYPE = '0000XSNJG0MQJHBF4QX1EFD6Y3';
    const CREATED = 1533041561;

    public static function mockVoucherResponse()
    {
        return [
            'id' => self::ID,
            'pin' => self::PIN,
            'value' => self::VALUE,
            'currency' => self::CURRENCY,
            'voucher_type' => self::VOUCHER_TYPE,
            'created' => self::CREATED,
        ];
    }
}
