<?php

namespace AESKW;


/**
 * Base class for AES key wrap algorithms with varying key sizes.
 *
 * @link https://tools.ietf.org/html/rfc3394
 */
abstract class Algorithm implements
    AESKeyWrapAlgorithm
{
    /**
     * Default initial value.
     *
     * @link https://tools.ietf.org/html/rfc3394#section-2.2.3.1
     * @var string
     */
    const DEFAULT_IV = "\xA6\xA6\xA6\xA6\xA6\xA6\xA6\xA6";

    /**
     * High order bytes of the alternative initial value for padding.
     *
     * @link https://tools.ietf.org/html/rfc5649#section-3
     * @var string
     */
    const AIV_HI = "\xA6\x59\x59\xA6";

    /**
     * Initial value.
     *
     * @var string $_iv
     */
    protected $_iv;

    /**
     * Constructor
     *
     * @param string $iv Initial value
     */
    public function __construct($iv = self::DEFAULT_IV)
    {
        if (strlen($iv) != 8) {
            throw new \UnexpectedValueException("IV size must be 64 bits.");
        }
        $this->_iv = $iv;
    }

    /**
     * Wrap a key using given key encryption key.
     *
     * Key length must be at least 64 bits (8 octets) and a multiple
     * of 64 bits (8 octets).
     * Use <i>wrapPad</i> to wrap a key of arbitrary length.
     *
     * Key encryption key must have a size of underlying AES algorithm,
     * ie. 128, 196 or 256 bits.
     *
     * @param string $key Key to wrap
     * @param string $kek Key encryption key
     * @throws \UnexpectedValueException If the key length is invalid
     * @return string Ciphertext
     */
    public function wrap($key, $kek)
    {
        $key_len = strlen($key);
        // rfc3394 dictates n to be at least 2
        if ($key_len < 16) {
            throw new \UnexpectedValueException(
                "Key length must be at least 16 octets.");
        }
        if (0 !== $key_len % 8) {
            throw new \UnexpectedValueException(
                "Key length must be a multiple of 64 bits.");
        }
        $this->_checkKEKSize($kek);
        // P = plaintext as 64 bit blocks
        $P = [];
        $i = 1;
        foreach (str_split($key, 8) as $val) {
            $P[$i++] = $val;
        }
        $C = $this->_wrapBlocks($P, $kek, $this->_iv);
        return implode("", $C);
    }

    /**
     * Check KEK size.
     *
     * @param string $kek
     * @throws \UnexpectedValueException
     * @return self
     */
    protected function _checkKEKSize($kek)
    {
        $len = $this->_keySize();
        if (strlen($kek) != $len) {
            throw new \UnexpectedValueException("KEK size must be $len bytes.");
        }
        return $this;
    }

    /**
     * Get key encryption key size.
     *
     * @return int
     */
    abstract protected function _keySize();

    /**
     * Apply Key Wrap to data blocks.
     *
     * Uses alternative version of the key wrap procedure described in the RFC.
     *
     * @link https://tools.ietf.org/html/rfc3394#section-2.2.1
     * @param string[] $P Plaintext, n 64-bit values <code>{P1, P2, ...,
     *        Pn}</code>
     * @param string $kek Key encryption key
     * @param string $iv Initial value
     * @return string[] Ciphertext, (n+1) 64-bit values <code>{C0, C1, ...,
     *         Cn}</code>
     */
    protected function _wrapBlocks(array $P, $kek, $iv)
    {
        $n = count($P);
        // Set A = IV
        $A = $iv;
        // For i = 1 to n
        //   R[i] = P[i]
        $R = $P;
        // For j = 0 to 5
        for ($j = 0; $j <= 5; ++$j) {
            // For i = 1 to n
            for ($i = 1; $i <= $n; ++$i) {
                // B = AES(K, A | R[i])
                $B = $this->_encrypt($kek, $A . $R[$i]);
                // A = MSB(64, B) ^ t where t = (n*j)+i
                $t = $n * $j + $i;
                $A = $this->_msb64($B) ^ $this->_uint64($t);
                // R[i] = LSB(64, B)
                $R[$i] = $this->_lsb64($B);
            }
        }
        // Set C[0] = A
        $C = [$A];
        // For i = 1 to n
        for ($i = 1; $i <= $n; ++$i) {
            // C[i] = R[i]
            $C[$i] = $R[$i];
        }
        return $C;
    }

    /**
     * Apply AES(K, W) operation (encrypt) to 64 bit block.
     *
     * @param string $kek
     * @param string $block
     * @throws \RuntimeException If encrypt fails
     * @return string
     */
    protected function _encrypt($kek, $block)
    {
        $str = openssl_encrypt($block, $this->_cipherMethod(), $kek,
            OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING);
        if (false === $str) {
            throw new \RuntimeException(
                "openssl_encrypt() failed: " . $this->_getLastOpenSSLError());
        }
        return $str;
    }

    /**
     * Get OpenSSL cipher method.
     *
     * @return string
     */
    abstract protected function _cipherMethod();

    /**
     * Get the latest OpenSSL error message.
     *
     * @return string
     */
    protected function _getLastOpenSSLError()
    {
        $msg = null;
        while (false !== ($err = openssl_error_string())) {
            $msg = $err;
        }
        return $msg;
    }

    /**
     * Take 64 most significant bits from value.
     *
     * @param string $val
     * @return string
     */
    protected function _msb64($val)
    {
        return substr($val, 0, 8);
    }

    /**
     * Convert number to 64 bit unsigned integer octet string with
     * most significant bit first.
     *
     * @param int $num
     * @return string
     */
    protected function _uint64($num)
    {
        // truncate on 32 bit hosts
        if (PHP_INT_SIZE < 8) {
            return "\0\0\0\0" . pack("N", $num);
        }
        return pack("J", $num);
    }

    /**
     * Take 64 least significant bits from value.
     *
     * @param string $val
     * @return string
     */
    protected function _lsb64($val)
    {
        return substr($val, -8);
    }

    /**
     * Unwrap a key from a ciphertext using given key encryption key.
     *
     * @param string $ciphertext Ciphertext of the wrapped key
     * @param string $kek Key encryption key
     * @throws \UnexpectedValueException If the ciphertext is invalid
     * @return string Unwrapped key
     */
    public function unwrap($ciphertext, $kek)
    {
        if (0 !== strlen($ciphertext) % 8) {
            throw new \UnexpectedValueException(
                "Ciphertext length must be a multiple of 64 bits.");
        }
        $this->_checkKEKSize($kek);
        // C = ciphertext as 64 bit blocks with integrity check value prepended
        $C = str_split($ciphertext, 8);
        list($A, $R) = $this->_unwrapBlocks($C, $kek);
        // check integrity value
        if ($A != $this->_iv) {
            throw new \UnexpectedValueException("Integrity check failed.");
        }
        // output the plaintext
        $P = array_slice($R, 1, null, true);
        return implode("", $P);
    }

    /**
     * Apply Key Unwrap to data blocks.
     *
     * Uses the index based version of key unwrap procedure
     * described in the RFC.
     *
     * Does not compute step 3.
     *
     * @link https://tools.ietf.org/html/rfc3394#section-2.2.2
     * @param string[] $C Ciphertext, (n+1) 64-bit values <code>{C0, C1, ...,
     *        Cn}</code>
     * @param string $kek Key encryption key
     * @throws \UnexpectedValueException
     * @return array Tuple of integrity value <code>A</code> and register
     *         <code>R</code>
     */
    protected function _unwrapBlocks(array $C, $kek)
    {
        $n = count($C) - 1;
        if (!$n) {
            throw new \UnexpectedValueException("No blocks.");
        }
        // Set A = C[0]
        $A = $C[0];
        // For i = 1 to n
        //   R[i] = C[i]
        $R = $C;
        // For j = 5 to 0
        for ($j = 5; $j >= 0; --$j) {
            // For i = n to 1
            for ($i = $n; $i >= 1; --$i) {
                // B = AES-1(K, (A ^ t) | R[i]) where t = n*j+i
                $t = $n * $j + $i;
                $B = $this->_decrypt($kek, ($A ^ $this->_uint64($t)) . $R[$i]);
                // A = MSB(64, B)
                $A = $this->_msb64($B);
                // R[i] = LSB(64, B)
                $R[$i] = $this->_lsb64($B);
            }
        }
        return array($A, $R);
    }

    /**
     * Apply AES-1(K, W) operation (decrypt) to 64 bit block.
     *
     * @param string $kek
     * @param string $block
     * @throws \RuntimeException If decrypt fails
     * @return string
     */
    protected function _decrypt($kek, $block)
    {
        $str = openssl_decrypt($block, $this->_cipherMethod(), $kek,
            OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING);
        if (false === $str) {
            throw new \RuntimeException(
                "openssl_decrypt() failed: " . $this->_getLastOpenSSLError());
        }
        return $str;
    }

    /**
     * Wrap a key of arbitrary length using given key encryption key.
     *
     * This variant of wrapping does not place any restriction on key size.
     *
     * Key encryption key has the same restrictions as with <i>wrap</i> method.
     *
     * @param string $key Key to wrap
     * @param string $kek Key encryption key
     * @throws \UnexpectedValueException If the key length is invalid
     * @return string Ciphertext
     */
    public function wrapPad($key, $kek)
    {
        $len = strlen($key);
        if (!$len) {
            throw new \UnexpectedValueException(
                "Key must have at least one octet.");
        }
        $this->_checkKEKSize($kek);
        // append padding
        if (0 != $len % 8) {
            $key = str_pad($key, $len + (8 - $len % 8), "\0", STR_PAD_RIGHT);
        }
        // compute AIV
        $mli = pack("N", $len);
        $aiv = self::AIV_HI . $mli;
        // if key length was less than 8 octets (padded key contains
        // exactly 8 octets), let the ciphertext be:
        // C[0] | C[1] = ENC(K, A | P[1]).
        if ($len <= 8) {
            return $this->_encrypt($kek, $aiv . $key);
        }
        // build plaintext blocks and apply normal wrapping with AIV as an
        // initial value
        $P = [];
        $i = 1;
        foreach (str_split($key, 8) as $val) {
            $P[$i++] = $val;
        }
        $C = $this->_wrapBlocks($P, $kek, $aiv);
        return implode("", $C);
    }

    /**
     * Unwrap a key from a padded ciphertext using given key encryption key.
     *
     * This variant of unwrapping must be used if the key was wrapped using
     * <i>wrapPad</i>.
     *
     * @param string $ciphertext Ciphertext of the wrapped and padded key
     * @param string $kek Key encryption key
     * @throws \UnexpectedValueException If the ciphertext is invalid
     * @return string Unwrapped key
     */
    public function unwrapPad($ciphertext, $kek)
    {
        if (0 !== strlen($ciphertext) % 8) {
            throw new \UnexpectedValueException(
                "Ciphertext length must be a multiple of 64 bits.");
        }
        $this->_checkKEKSize($kek);
        list($P, $A) = $this->_unwrapPaddedCiphertext($ciphertext, $kek);
        // check message integrity
        $this->_checkPaddedIntegrity($A);
        // verify padding
        $len = $this->_verifyPadding($P, $A);
        // remove padding and return unwrapped key
        return substr(implode("", $P), 0, $len);
    }

    /**
     * Unwrap the padded ciphertext producing plaintext and integrity value.
     *
     * @param string $ciphertext Ciphertext
     * @param string $kek Encryption key
     * @return array Tuple of plaintext <code>{P1, P2, ..., Pn}</code> and
     *         integrity value <code>A</code>
     */
    protected function _unwrapPaddedCiphertext($ciphertext, $kek)
    {
        // split to blocks
        $C = str_split($ciphertext, 8);
        $n = count($C) - 1;
        // if key consists of only one block, recover AIV and padded key as:
        // A | P[1] = DEC(K, C[0] | C[1])
        if ($n == 1) {
            $P = str_split($this->_decrypt($kek, $C[0] . $C[1]), 8);
            $A = $P[0];
            unset($P[0]);
        } else {
            // apply normal unwrapping
            list($A, $R) = $this->_unwrapBlocks($C, $kek);
            $P = array_slice($R, 1, null, true);
        }
        return [$P, $A];
    }

    /**
     * Check that the integrity check value of the padded key is correct.
     *
     * @param string $A
     * @throws \UnexpectedValueException
     */
    protected function _checkPaddedIntegrity($A)
    {
        // check that MSB(32,A) = A65959A6
        if (substr($A, 0, 4) != self::AIV_HI) {
            throw new \UnexpectedValueException("Integrity check failed.");
        }
    }

    /**
     * Verify that the padding of the plaintext is valid.
     *
     * @param array $P Plaintext, n 64-bit values <code>{P1, P2, ...,
     *        Pn}</code>
     * @param string $A Integrity check value
     * @throws \UnexpectedValueException
     * @return int Message length without padding
     */
    protected function _verifyPadding(array $P, $A)
    {
        // extract mli
        $mli = substr($A, -4);
        $len = unpack("N1", $mli)[1];
        // check under and overflow
        $n = count($P);
        if (8 * ($n - 1) >= $len || $len > 8 * $n) {
            throw new \UnexpectedValueException("Invalid message length.");
        }
        // if key is padded
        $b = 8 - ($len % 8);
        if ($b < 8) {
            // last block (note that the first index in P is 1)
            $Pn = $P[$n];
            // check that padding consists of zeroes
            if (substr($Pn, -$b) != str_repeat("\0", $b)) {
                throw new \UnexpectedValueException("Invalid padding.");
            }
        }
        return $len;
    }
}
