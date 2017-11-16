<?php

namespace wechat\Object;

class BuiltinIPList extends \wechat\WXPBGeneratedMessage
{
    /**
     * @var integer (@dynamic, nonatomic) unsigned
     */
    public $longConnectIpcount;

    /**
     * @var integer (@dynamic, nonatomic) unsigned
     */
    public $shortConnectIpcount;

    /**
     * @var integer (@dynamic, nonatomic) unsigned
     */
    public $seq;

    /**
     * @var \wechat\Object\BuiltinIP[] (retain, @dynamic, nonatomic)
     */
    public $longConnectIplist;

    /**
     * @var \wechat\Object\BuiltinIP[] (retain, @dynamic, nonatomic)
     */
    public $shortConnectIplist;

    public function __construct()
    {
        $this->classInfo = new \wechat\PBClassInfo();

        $this->classInfo->nameProperty[] = 'longConnectIpcount';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0x1, 0x2, 0xD, 0x0, 0x0, '');

        $this->classInfo->nameProperty[] = 'shortConnectIpcount';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0x2, 0x2, 0xD, 0x0, 0x0, '');

        $this->classInfo->nameProperty[] = 'seq';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0x5, 0x2, 0xD, 0x0, 0x0, '');

        $this->classInfo->nameProperty[] = 'longConnectIplist';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0x3, 0x3, 0xB, 0x0, 0x0, 'BuiltinIP');

        $this->classInfo->nameProperty[] = 'shortConnectIplist';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0x4, 0x3, 0xB, 0x0, 0x0, 'BuiltinIP');

        parent::__construct();
    }
}