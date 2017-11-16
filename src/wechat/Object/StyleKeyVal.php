<?php

namespace wechat\Object;

class StyleKeyVal extends \wechat\WXPBGeneratedMessage
{
    /**
     * @var integer (@dynamic, nonatomic) unsigned
     */
    public $key;

    /**
     * @var string (retain, @dynamic, nonatomic)
     */
    public $val;

    public function __construct()
    {
        $this->classInfo = new \wechat\PBClassInfo();

        $this->classInfo->nameProperty[] = 'key';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0x1, 0x2, 0xD, 0x0, 0x0, '');

        $this->classInfo->nameProperty[] = 'val';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0x2, 0x1, 0x9, 0x0, 0x0, '');

        parent::__construct();
    }
}