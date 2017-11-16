<?php

namespace wechat\Object;

class SKBuiltinString_t extends \wechat\WXPBGeneratedMessage
{
    /**
     * @var string (retain, @dynamic, nonatomic)
     */
    public $string;

    public function __construct($string = NULL)
    {
        $this->classInfo = new \wechat\PBClassInfo();

        $this->classInfo->nameProperty[] = 'string';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0x1, 0x1, 0x9, 0x0, 0x0, '');
        $this->string = $string;
        parent::__construct();

    }
}
