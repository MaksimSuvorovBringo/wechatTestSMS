<?php


/**
 *
 * @link https://tools.ietf.org/html/rfc3394#section-4
 */
class RFC3394TestVectorsTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provider
     *
     * @param string $kek Key encryption key in hex encoding
     * @param string $key Key to wrap in hex encoding
     * @param string $result Expected result in hex encoding
     */
    public function testTestVectors($kek, $key, $result)
    {
        $kek = hex2bin($kek);
        $key = hex2bin($key);
        $result = hex2bin(preg_replace('/\s+/', '', $result));
        $bits = strlen($kek) * 8;
        $cls = 'AESKW\AESKW' . $bits;
        $algo = new $cls();
        $ciphertext = $algo->wrap($key, $kek);
        $this->assertEquals($result, $ciphertext, "Key Wrap");
        $plaintext = $algo->unwrap($ciphertext, $kek);
        $this->assertEquals($key, $plaintext, "Key Unwrap");
    }

    public function provider()
    {
        return array(
            /* @formatter:off */
            // 4.1 Wrap 128 bits of Key Data with a 128-bit KEK
            [
                "000102030405060708090A0B0C0D0E0F",
                "00112233445566778899AABBCCDDEEFF",
                "1FA68B0A8112B447 AEF34BD8FB5A7B82 9D3E862371D2CFE5"
            ],
            // 4.2 Wrap 128 bits of Key Data with a 192-bit KEK
            [
                "000102030405060708090A0B0C0D0E0F1011121314151617",
                "00112233445566778899AABBCCDDEEFF",
                "96778B25AE6CA435 F92B5B97C050AED2 468AB8A17AD84E5D"
            ],
            // 4.3 Wrap 128 bits of Key Data with a 256-bit KEK
            [
                "000102030405060708090A0B0C0D0E0F101112131415161718191A1B1C1D1E1F",
                "00112233445566778899AABBCCDDEEFF",
                "64E8C3F9CE0F5BA2 63E9777905818A2A 93C8191E7D6E8AE7"
            ],
            // 4.4 Wrap 192 bits of Key Data with a 192-bit KEK
            [
                "000102030405060708090A0B0C0D0E0F1011121314151617",
                "00112233445566778899AABBCCDDEEFF0001020304050607",
                "031D33264E15D332 68F24EC260743EDC E1C6C7DDEE725A93 6BA814915C6762D2"
            ],
            // 4.5 Wrap 192 bits of Key Data with a 256-bit KEK
            [
                "000102030405060708090A0B0C0D0E0F101112131415161718191A1B1C1D1E1F",
                "00112233445566778899AABBCCDDEEFF0001020304050607",
                "A8F9BC1612C68B3F F6E6F4FBE30E71E4 769C8B80A32CB895 8CD5D17D6B254DA1"
            ],
            // 4.6 Wrap 256 bits of Key Data with a 256-bit KEK
            [
                "000102030405060708090A0B0C0D0E0F101112131415161718191A1B1C1D1E1F",
                "00112233445566778899AABBCCDDEEFF000102030405060708090A0B0C0D0E0F",
                "28C9F404C4B810F4 CBCCB35CFB87F826 3F5786E2D80ED326 CBC7F0E71A99F43B FB988B9B7A02DD21"
            ]
            /* @formatter:on */
        );
    }
}
