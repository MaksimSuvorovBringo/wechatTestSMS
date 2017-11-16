<?php

namespace wechat\Object;

class ShowStyleKey extends \wechat\WXPBGeneratedMessage
{
    /**
     * @var integer (@dynamic, nonatomic) unsigned
     */
    public $keyCount;

    /**
     * @var \wechat\Object\StyleKeyVal[] (retain, @dynamic,    nonatomic)
     */
    public $key;

    public function __construct()
    {
        $this->classInfo = new \wechat\PBClassInfo();

        $this->classInfo->nameProperty[] = 'keyCount';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0x1, 0x2, 0xD, 0x0, 0x0, '');

        $this->classInfo->nameProperty[] = 'key';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0x2, 0x3, 0xB, 0x0, 0x0, 'StyleKeyVal');

        parent::__construct();
    }
}