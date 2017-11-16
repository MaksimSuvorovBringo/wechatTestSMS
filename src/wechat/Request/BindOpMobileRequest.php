<?php

namespace wechat\Request;

class BindOpMobileRequest extends \wechat\WXPBGeneratedMessage
{
    /**
     * @var \wechat\Request\BaseRequest (retain, @dynamic, nonatomic)
     */
    public $baseRequest;

    /**
     * @var string (retain, @dynamic,    nonatomic)
     */
    public $userName;

    /**
     * @var string (retain, @dynamic, nonatomic)
     */
    public $mobile;

    /**
     * @var integer (@dynamic, nonatomic)
     */
    public $opcode;

    /**
     * @var string (retain, @dynamic,    nonatomic)
     */
    public $verifycode;

    /**
     * @var integer (@dynamic, nonatomic)
     */
    public $dialFlag;

    /**
     * @var string (retain, @dynamic, nonatomic)
     */
    public $dialLang;

    /**
     * @var string (retain, @dynamic,    nonatomic)
     */
    public $authTicket;

    /**
     * @var integer (@dynamic, nonatomic) unsigned
     */
    public $forceReg;

    /**
     * @var string (retain, @dynamic, nonatomic)
     */
    public $safeDeviceName;

    /**
     * @var string (retain, @dynamic, nonatomic)
     */
    public $safeDeviceType;

    /**
     * @var \wechat\Object\SKBuiltinBuffer_t (retain, @dynamic, nonatomic)
     */
    public $randomEncryKey;

    /**
     * @var string (retain, @dynamic, nonatomic)
     */
    public $language;

    /**
     * @var integer (@dynamic, nonatomic) unsigned
     */
    public $inputMobileRetrys;

    /**
     * @var integer (@dynamic, nonatomic) unsigned
     */
    public $adjustRet;

    /**
     * @var string (retain, @dynamic, nonatomic)
     */
    public $clientSeqId;

    public function __construct()
    {
        $this->classInfo = new \wechat\PBClassInfo();

        $this->classInfo->nameProperty[] = 'baseRequest';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0x1, 0x2, 0xB, 0x0, 0x0, 'BaseRequest');

        $this->classInfo->nameProperty[] = 'userName';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0x2, 0x1, 0x9, 0x0, 0x0, '');

        $this->classInfo->nameProperty[] = 'mobile';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0x3, 0x1, 0x9, 0x0, 0x0, '');

        $this->classInfo->nameProperty[] = 'opcode';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0x4, 0x2, 0x5, 0x0, 0x0, '');

        $this->classInfo->nameProperty[] = 'verifycode';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0x5, 0x1, 0x9, 0x0, 0x0, '');

        $this->classInfo->nameProperty[] = 'dialFlag';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0x6, 0x1, 0x5, 0x0, 0x0, '');

        $this->classInfo->nameProperty[] = 'dialLang';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0x7, 0x1, 0x9, 0x0, 0x0, '');

        $this->classInfo->nameProperty[] = 'authTicket';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0x8, 0x1, 0x9, 0x0, 0x0, '');

        $this->classInfo->nameProperty[] = 'forceReg';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0x9, 0x1, 0xD, 0x0, 0x0, '');

        $this->classInfo->nameProperty[] = 'safeDeviceName';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0xA, 0x1, 0x9, 0x0, 0x0, '');

        $this->classInfo->nameProperty[] = 'safeDeviceType';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0xB, 0x1, 0x9, 0x0, 0x0, '');

        $this->classInfo->nameProperty[] = 'randomEncryKey';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0xC, 0x1, 0xB, 0x0, 0x0, 'SKBuiltinBuffer_t');

        $this->classInfo->nameProperty[] = 'language';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0xD, 0x1, 0x9, 0x0, 0x0, '');

        $this->classInfo->nameProperty[] = 'inputMobileRetrys';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0xE, 0x1, 0xD, 0x0, 0x0, '');

        $this->classInfo->nameProperty[] = 'adjustRet';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0xF, 0x1, 0xD, 0x0, 0x0, '');

        $this->classInfo->nameProperty[] = 'clientSeqId';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0x10, 0x1, 0x9, 0x0, 0x0, '');

        parent::__construct();
    }
}