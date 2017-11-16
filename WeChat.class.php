<?php

class WeChat
{
    /**
     * @var string RSA Public Key is hardcoded in client binary
     */
    public $rsaPublicKey = <<<EOF
-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAtXkUc/36zOQmBYQBthJa
PW/t12x90bBCanPYpBgrKeptBfT16NmaTT0cPlzzyMs83fk1ZDyU04kniBsUTQTz
EPEzB9GuY6EAonl6cUwNHipaDvd5/D1vfTwzlidr8n2m1m4mlqZVfv1LYZDHJolN
Nc5VnhR5abrASv67DjojWyx5WsapgY4UozpEaPj/ar6KVKdBgAQr8P04Qn9wtoG5
QxoJnndGGNRV8U0fdRIVd9rmbDhToqqcTw+cIhpm9kpG1faLDVDyLH5PoNhASLL5
F59LhkQqJyDI/ie8aMXGOE3MM2+XkU8niLkF5f6Yxbt1RIiw9rCUIbsnv/UY7w6S
mQIDAQAB
-----END PUBLIC KEY-----
EOF;

    /**
     * @var resource Curl handle
     */
    public $ch = null;
    public $channel;

    /**
     * Encrypt plaintext with hardcoded RSA public key
     * @param string $plaintext plaintext as byte stream
     * @return string ciphertext
     */
    public function RSAEncrypt($plaintext)
    {
        $publicKey = openssl_get_publickey($this->rsaPublicKey);
        $detail = openssl_pkey_get_details($publicKey);
        $bits = $detail['bits'];

        $maxLength = $bits / 8 - 0x0C;
        $ciphertext = '';

        while (strlen($plaintext) > 0) {
            $input = substr($plaintext, 0, $maxLength);
            $plaintext = substr($plaintext, $maxLength);
            openssl_public_encrypt($input, $encrypted, $publicKey);
            $ciphertext .= $encrypted;
        }

        return $ciphertext;
    }

    /**
     * Encrypt plaintext with AES key
     * @param string $plaintext plaintext as byte stream
     * @param string $key AES key as byte stream
     * @return string ciphertext
     */

    public function AESEncrypt($plaintext, $key)
    {

        $ciphertext = openssl_encrypt($plaintext, 'aes-128-cbc', $key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $key);

        return $ciphertext;


    }

    /**
     * Decrypt ciphertext with AES key
     * @param string $ciphertext ciphertext as byte stream
     * @param string $key AES key as byte stream
     * @return string plaintext
     */
    public function AESDecrypt($ciphertext, $key)
    {

        $plaintext = openssl_decrypt($ciphertext, 'aes-128-cbc', $key, OPENSSL_RAW_DATA, $key);
        if (substr($plaintext, 0, 2) == "\x78\x9c") {
            $plaintext = gzuncompress($plaintext);
        }
        return $plaintext;
    }


    /**
     * Process request to WeChat server
     * @param AccountUser $accountUser request context
     * @param string $data request payload
     * @param string $uri request endpoint uri
     * @return string response from server
     * @throws Exception if query failed
     */
    public function request(AccountUser $accountUser, $data, $uri)
    {
        switch (true) {
            case 1 | strpos($accountUser->phoneNumber, '+7') === 0:
                $host = 'hkshort.weixin.qq.com';
//                $host = 'hkminorshort.weixin.qq.com';
                break;
            case strpos($accountUser->phoneNumber, '+86') === 0:
            case strpos($accountUser->phoneNumber, '+85') === 0:
                $host = 'szextshort.weixin.qq.com';

                break;
            default:
                throw new LogicException('Cannot detect hostname for phone number, country is not supported yet!');
                break;
        }

        if (null === $this->ch) {
            $this->ch = curl_init();
        }
        curl_setopt($this->ch, CURLOPT_URL, 'http://' . $host . '/cgi-bin/micromsg-bin/' . $uri);
        curl_setopt($this->ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($this->ch, CURLOPT_USERAGENT, 'MicroMessenger Client');
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($this->ch, CURLOPT_HEADER, 0);
        curl_setopt($this->ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, [
            'Accept: */*',
            'Cache-Control: no-cache',
            'Connection: close',
            'Content-Length: ' . strlen($data),
            'Content-Type: application/octet-stream',
        ]);


        $accountUser->proxy = '';

        if ($accountUser->proxy) {
            curl_setopt($this->ch, CURLOPT_PROXY, $accountUser->proxy);
            curl_setopt($this->ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS4);
        }

        // retry request for 10 times
        $retry = 10;
        do {
            $receivedData = curl_exec($this->ch);
            $retry--;
            if ($receivedData === false && $retry === 0) {
                throw new LogicException('Cannot process request to WeChat server');
            }
        } while ($receivedData === false);

        return $receivedData;
    }

    /**
     * Build request to server from params
     * @param array $params param of request
     * @return string request
     * @throws Exception if failed
     */
    public function buildRequest($params)
    {
        $request = '';
        foreach ($params as $value) {
            if (is_array($value)) {
                $key = array_keys($value)[0];
                switch ($key) {
                    case 'string':
                        $request .= $this->packInt7Bit(strlen($value[$key])) . $value[$key];
                        break;
                    case '7bit':
                        $request .= $this->packInt7Bit($value[$key]);
                        break;
                    case 'number':
                        /** @noinspection PhpParamsInspection */
                        $request .= gmp_export(gmp_init($value[$key]));
                        break;
                    default:
                        throw new Exception('Not supported');
                        break;
                }
            } else {
                $request .= $value;
            }
        }
        return $request;
    }

    /**
     * Pack int to 7bit stream
     * @param int $int integer to pack
     * @return string binary stream
     */
    public function packInt7Bit($int)
    {
        $packed = '';
        do {
            $int7bit = $int & 0xff;
            if ($int >= 0x80) {
                $int7bit |= 0x80;
            }
            $packed = $packed << 8 | $int7bit;
            $int = $int >> 7;

        } while ($int > 0);

        /** @noinspection PhpParamsInspection */
        $packed = gmp_export(gmp_init($packed));
        return $packed;

    }
}