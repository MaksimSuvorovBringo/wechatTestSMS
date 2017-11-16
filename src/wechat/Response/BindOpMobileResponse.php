<?php

namespace wechat\Response;

class BindOpMobileResponse extends \wechat\WXPBGeneratedMessage
{
    /**
     * @var \wechat\Response\BaseResponse (retain, @dynamic, nonatomic)
     */
    public $baseResponse;

    /**
     * @var string (retain, @dynamic, nonatomic)
     */
    public $ticket;

    /**
     * @var string (retain, @dynamic, nonatomic)
     */
    public $smsNo;

    /**
     * @var integer (@dynamic, nonatomic) unsigned
     */
    public $needSetPwd;

    /**
     * @var string (retain, @dynamic, nonatomic)
     */
    public $pwd;

    /**
     * @var string (retain, @dynamic, nonatomic)
     */
    public $username;

    /**
     * @var \wechat\Object\HostList (retain, @dynamic, nonatomic)
     */
    public $newHostList;

    /**
     * @var \wechat\Object\BuiltinIPList (retain, @dynamic, nonatomic)
     */
    public $builtinIplist;

    /**
     * @var \wechat\Object\NetworkControl (retain, @dynamic, nonatomic)
     */
    public $networkControl;

    /**
     * @var string (retain, @dynamic,    nonatomic)
     */
    public $authTicket;

    /**
     * @var integer (@dynamic, nonatomic) unsigned
     */
    public $safeDevice;

    /**
     * @var string (retain,    @dynamic, nonatomic)
     */
    public $cc;

    /**
     * @var integer (@dynamic, nonatomic) unsigned
     */
    public $obsoleteItem1;

    /**
     * @var \wechat\Object\SafeDeviceList (retain, @dynamic, nonatomic)
     */
    public $safeDeviceList;

    /**
     * @var string (retain, @dynamic,    nonatomic)
     */
    public $pureMobile;

    /**
     * @var string (retain, @dynamic, nonatomic)
     */
    public $formatedMobile;

    /**
     * @var \wechat\Object\ShowStyleKey (retain,    @dynamic, nonatomic)
     */
    public $showStyle;

    public function __construct()
    {
        $this->classInfo = new \wechat\PBClassInfo();

        $this->classInfo->nameProperty[] = 'baseResponse';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0x1, 0x2, 0xB, 0x0, 0x0, 'BaseResponse');

        $this->classInfo->nameProperty[] = 'ticket';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0x2, 0x1, 0x9, 0x0, 0x0, '');

        $this->classInfo->nameProperty[] = 'smsNo';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0x3, 0x1, 0x9, 0x0, 0x0, '');

        $this->classInfo->nameProperty[] = 'needSetPwd';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0x4, 0x1, 0xD, 0x0, 0x0, '');

        $this->classInfo->nameProperty[] = 'pwd';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0x5, 0x1, 0x9, 0x0, 0x0, '');

        $this->classInfo->nameProperty[] = 'username';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0x6, 0x1, 0x9, 0x0, 0x0, '');

        $this->classInfo->nameProperty[] = 'newHostList';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0x7, 0x1, 0xB, 0x0, 0x0, 'HostList');

        $this->classInfo->nameProperty[] = 'builtinIplist';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0x8, 0x1, 0xB, 0x0, 0x0, 'BuiltinIPList');

        $this->classInfo->nameProperty[] = 'networkControl';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0x9, 0x1, 0xB, 0x0, 0x0, 'NetworkControl');

        $this->classInfo->nameProperty[] = 'authTicket';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0xA, 0x1, 0x9, 0x0, 0x0, '');

        $this->classInfo->nameProperty[] = 'safeDevice';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0xB, 0x1, 0xD, 0x0, 0x0, '');

        $this->classInfo->nameProperty[] = 'cc';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0xC, 0x1, 0x9, 0x0, 0x0, '');

        $this->classInfo->nameProperty[] = 'obsoleteItem1';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0xD, 0x1, 0xD, 0x0, 0x0, '');

        $this->classInfo->nameProperty[] = 'safeDeviceList';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0xE, 0x1, 0xB, 0x0, 0x0, 'SafeDeviceList');

        $this->classInfo->nameProperty[] = 'pureMobile';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0xF, 0x1, 0x9, 0x0, 0x0, '');

        $this->classInfo->nameProperty[] = 'formatedMobile';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0x10, 0x1, 0x9, 0x0, 0x0, '');

        $this->classInfo->nameProperty[] = 'showStyle';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0x11, 0x1, 0xB, 0x0, 0x0, 'ShowStyleKey');

        parent::__construct();
    }
}