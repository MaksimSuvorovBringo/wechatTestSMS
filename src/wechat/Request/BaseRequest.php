<?php

namespace wechat\Request;

class BaseRequest extends \wechat\WXPBGeneratedMessage
{
    /**
     * @var string (retain, @dynamic, nonatomic)
     */
    public $sessionKey;

    /**
     * @var integer (@dynamic, nonatomic) unsigned
     */
    public $uin;

    /**
     * @var string (retain, @dynamic, nonatomic)
     */
    public $deviceId;

    /**
     * @var integer (@dynamic, nonatomic)
     */
    public $clientVersion;

    /**
     * @var string (retain, @dynamic, nonatomic)
     */
    public $deviceType;

    /**
     * @var integer (@dynamic, nonatomic) unsigned
     */
    public $scene;

    public function __construct()
    {
        $this->classInfo = new \wechat\PBClassInfo();

        $this->classInfo->nameProperty[] = 'sessionKey';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0x1, 0x2, 0xC, 0x0, 0x0, '');

        $this->classInfo->nameProperty[] = 'uin';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0x2, 0x2, 0xD, 0x0, 0x0, '');

        $this->classInfo->nameProperty[] = 'deviceId';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0x3, 0x2, 0xC, 0x0, 0x0, '');

        $this->classInfo->nameProperty[] = 'clientVersion';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0x4, 0x2, 0x5, 0x0, 0x0, '');

        $this->classInfo->nameProperty[] = 'deviceType';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0x5, 0x2, 0xC, 0x0, 0x0, '');

        $this->classInfo->nameProperty[] = 'scene';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0x6, 0x1, 0xD, 0x0, 0x0, '');

        parent::__construct();
    }
}