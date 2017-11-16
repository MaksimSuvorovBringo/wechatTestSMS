<?php


/**
 *
 * @link https://tools.ietf.org/html/rfc5649#section-6
 */
class RFC5649ExamplesTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provider
     *
     * @param string $kek Key encryption key in hex encoding
     * @param string $key Key to wrap in hex encoding
     * @param string $result Expected result in hex encoding
     */
    public function testExamples($kek, $key, $result)
    {
        $kek = hex2bin(preg_replace('/\s+/', '', $kek));
        $key = hex2bin(preg_replace('/\s+/', '', $key));
        $result = hex2bin(preg_replace('/\s+/', '', $result));
        $bits = strlen($kek) * 8;
        $cls = 'AESKW\AESKW' . $bits;
        $algo = new $cls();
        $ciphertext = $algo->wrapPad($key, $kek);
        $this->assertEquals($result, $ciphertext, "Key Wrap");
        $plaintext = $algo->unwrapPad($ciphertext, $kek);
        $this->assertEquals($key, $plaintext, "Key Unwrap");
    }

    public function provider()
    {
        return array(
            /* @formatter:off */
            // The first example wraps 20 octets of key data with a 192-bit KEK.
            [
                "5840df6e29b02af1 ab493b705bf16ea1 ae8338f4dcc176a8",
                "c37b7e6492584340 bed1220780894115 5068f738",
                "138bdeaa9b8fa7fc 61f97742e72248ee 5ae6ae5360d1ae6a 5f54f373fa543b6a"
            ],
            // The second example wraps 7 octets of key data with a 192-bit KEK.
            [
                "5840df6e29b02af1 ab493b705bf16ea1 ae8338f4dcc176a8",
                "466f7250617369",
                "afbeb0f07dfbf541 9200f2ccb50bb24f"
            ]
            /* @formatter:on */
        );
    }
}
