<?php

namespace wechat\Object;

class SafeDeviceList extends \wechat\WXPBGeneratedMessage
{
    /**
     * @var integer (@dynamic, nonatomic) unsigned
     */
    public $count;

    /**
     * @var \wechat\Object\SafeDevice[] (retain, @dynamic, nonatomic)
     */
    public $list;

    public function __construct()
    {
        $this->classInfo = new \wechat\PBClassInfo();

        $this->classInfo->nameProperty[] = 'count';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0x1, 0x2, 0xD, 0x0, 0x0, '');

        $this->classInfo->nameProperty[] = 'list';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0x2, 0x3, 0xB, 0x0, 0x0, 'SafeDevice');

        parent::__construct();
    }
}