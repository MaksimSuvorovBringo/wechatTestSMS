<?php
require_once dirname(__FILE__) . '/vendor/autoload.php';


class AccountUser
{
    public $host;
    public $syncCount;
    public $syncHeader;
    public $phoneNumber;
    public $deviceId;
    public $imei;
    public $clientSeqId;
    public $deviceType;
    public $deviceName;
    public $aesKey;
    public $language;
    public $ticket;
    public $softType;
    public $timeZone;
    public $country;
    public $password;
    public $bundleId;
    public $clientVersion;
    public $srvID;
    public $cryptUin;
    public $clientPrivKey;
    public $clientECDHPublicKey;
    public $shareKey;
    public $UUID;
    public $autoAuthKey;
    public $contacts;
    public $advId;
    public $wxId;
    public $timeStamp;
    public $brandListVer;
    public $proxy;
    public $registerOk;
    public $tempPassword;
    public $syncKey;
    public $smscode;
    public $client;
    public $iphoneVer;
    public $commands = [];
    public $channel;


    public function __construct($phoneNumber)
    {
        $this->host = 'szextshort.weixin.qq.com';
        $this->client = new WeChat();
        $this->timeStamp = time();
        $this->phoneNumber = $phoneNumber;
        $this->imei = md5($phoneNumber . 'client salt');
        $this->advId = md5ToUUID(md5($this->phoneNumber . 'advId Salt'));
        $this->deviceId = '49' . substr($this->imei, 2);
        $this->deviceType = 'iPhone OS7.1.2';
        $this->bundleId = 'com.tencent.xin';
        $this->deviceName = 'Nagibator';
        $this->language = 'ru';
        $this->ticket = '';
        $this->iphoneVer = 'iPhone3,1';
        $this->softType = strtr("<softtype><k3>:version</k3><k9>:platform</k9><k10>:cpuCount</k10><k19>:UUID</k19><k20>:advId</k20><k21>:netName</k21><k22>:operatorName</k22><k24>:MacAddrress</k24><k33>:displayName</k33><k47>:netType</k47><k50>:jailBroken</k50><k51>:bundleID</k51><k54>:modelName</k54></softtype>", array(
            ':version' => '9.1',
            ':platform' => 'iPhone',
            ':cpuCount' => '2',
            ':UUID' => md5ToUUID(md5($this->phoneNumber . 'UUID Salt')),
            ':advId' => $this->advId,
            ':netName' => substr(md5($this->phoneNumber . 'Net salt'), 0, 6),
            ':operatorName' => 'Beeline',
            ':MacAddrress' => md5ToMac(md5($this->phoneNumber . 'Mac Salt')),
            ':displayName' => "\xe5\xbe\xae\xe4\xbf\xa1",//два иероглифа в переводе WeChat!!!
            ':netType' => '1',
            ':jailBroken' => '0',
            ':bundleID' => 'com.tencent.xin',
            ':modelName' => 'iPhone8,1',
        ));
        $this->timeZone = '4.00';
        $this->country = 'RU';
        $this->srvID = '';
        $this->cryptUin = 0;
        $this->clientVersion = 0x17050520;
        $this->clientSeqId = $this->imei . '-' . strval($this->timeStamp);
        $this->UUID = md5ToUUID(md5($this->phoneNumber . 'UUID Salt'));

    }

    function GetNewAESKey()
    {
        $this->aesKey = hex2bin(getRandomHexstring(0x10));

    }

    public function getVerifyCode()
    {
        $bindOpMobileRequest = new \wechat\Request\BindOpMobileRequest();
        $baseRequest = $this->createBaseRequest();
        $this->aesKey = random_bytes(0x10);
        $baseRequest->sessionKey = '';
        $baseRequest->scene = 0;
        $bindOpMobileRequest->baseRequest = $baseRequest;
        $bindOpMobileRequest->mobile = $this->phoneNumber;
        $bindOpMobileRequest->opcode = 14;
        $bindOpMobileRequest->safeDeviceName = $this->deviceName;
        $bindOpMobileRequest->safeDeviceType = 'iPhone';
        $bindOpMobileRequest->randomEncryKey = new \wechat\Object\SKBuiltinBuffer_t();
        $bindOpMobileRequest->randomEncryKey->iLen = strlen($this->aesKey);
        $bindOpMobileRequest->randomEncryKey->buffer = $this->aesKey;
        $bindOpMobileRequest->language = $this->language;
        $bindOpMobileRequest->inputMobileRetrys = 5;
        $bindOpMobileRequest->adjustRet = 0;
        $bindOpMobileRequest->clientSeqId = $this->clientSeqId;
        $serializedData = $bindOpMobileRequest->serializedData();
        $header = $this->computeHeader($serializedData, 0x91, 2);
        $dataToSend = $header . $this->client->RSAEncrypt($serializedData);
        $response = $this->client->request($this, $dataToSend, 'bindopmobileforreg');
        $response = deleteHeaderFromResponse($response);
        $response = $this->client->AESDecrypt($response, $this->aesKey);
        $bindOpMobileResponse = new \wechat\Response\BindOpMobileResponse();
        $bindOpMobileResponse->mergeFromData($response);
        echo $bindOpMobileResponse->baseResponse->errMsg->string."\n";

//        $bindOpMobileResponse->dump();

    }


    public function createBaseRequest()
    {
        $result = new wechat\Request\BaseRequest();
        $result->uin = $this->cryptUin;
        $result->sessionKey = $this->aesKey;
        $result->deviceId = hex2bin($this->deviceId);
        $result->clientVersion = $this->clientVersion;
        $result->deviceType = $this->deviceType;
        $result->scene = 1;
        return $result;

    }

    function computeHeader($serializedData, $uiCgi = 0x20A, $wasCompressed = 2)
    {

        function SerializeNumber($number)
        {
            $result = '';
            do {


                if ($number >= 128) {
                    $b = ($number & 0xff) | 0x80;
                } else {
                    $b = $number & 0xff;
                }
                $result .= bin2hex(chr($b));
                $number = ($number >> 7) | (0 << 25);
            } while ($number > 0);
            return $result;
        }

        $requestHeader = 'bf';
        $serializedData = bin2hex($serializedData);
        $requestHeader .= bin2hex(strrev(hex2bin(dechex(((strlen($this->srvID)) << 8) & 0xF00 | $wasCompressed & 3 & 3 | 4 * (8 & 0x3F) | (5 << 12)))));
        $requestHeader .= dechex($this->clientVersion);
        if ($this->cryptUin === 0) {
            $requestHeader .= '00000000';
        } else {
            $requestHeader .= alignString(dechex($this->cryptUin));
        }
        if ($this->srvID !== '') {
            $requestHeader .= $this->srvID;
        }
        $requestHeader .= SerializeNumber($uiCgi);
        $requestHeader .= SerializeNumber(strlen(hex2bin($serializedData)));
        if ($wasCompressed === 1) {
            $dataLen = strlen((gzcompress(hex2bin($serializedData))));
        } else {
            $dataLen = strlen(hex2bin($serializedData));
        }
        $requestHeader .= SerializeNumber($dataLen);
        if ($this->cryptUin === 0) {
            $requestHeader .= '87010100';
        } else {
            $requestHeader .= '0001';
            $hash = hexdec($this->getHash($serializedData));
            $requestHeader .= SerializeNumber($hash);
        }
        $requestHeader .= '010a0080';
        $headerLen = strlen(hex2bin($requestHeader));
        $requestHeader = substr_replace($requestHeader, dechex((($headerLen << 2) | (hexdec(substr($requestHeader, 2, 2))) & 0xFF03)), 2, 2);
        return hex2bin($requestHeader);

    }

    function getHash($serializedData)
    {

        return bin2hex(mhash(18,
            hex2bin(md5((hex2bin(resizeByteString(dechex(strlen($serializedData) / 2), 4))) .
                    $this->shareKey .
                    hex2bin(md5((hex2bin(alignString(dechex($this->cryptUin)))) .
                        $this->shareKey))) .
                $serializedData)));
    }
}