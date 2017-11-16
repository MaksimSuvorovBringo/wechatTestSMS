<?php

use AESKW\AESKW128;


class CustomIVTest extends PHPUnit_Framework_TestCase
{
    const IV = "1122334466778899";

    const KEY = "PasswordPassword";

    const KEK = "00112233445566778899aabbccddeeff";

    public function testWrap()
    {
        $algo = new AESKW128(hex2bin(self::IV));
        $data = $algo->wrap(self::KEY, hex2bin(self::KEK));
        $this->assertTrue(is_string($data));
        return $data;
    }

    /**
     * @depends testWrap
     *
     * @param string $data
     */
    public function testUnwrap($data)
    {
        $algo = new AESKW128(hex2bin(self::IV));
        $key = $algo->unwrap($data, hex2bin(self::KEK));
        $this->assertEquals(self::KEY, $key);
    }
}
