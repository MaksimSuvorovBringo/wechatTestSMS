<?php

namespace wechat\Response;

class BaseResponse extends \wechat\WXPBGeneratedMessage
{
    /**
     * @var integer (@dynamic, nonatomic)
     */
    public $ret;

    /**
     * @var \wechat\Object\SKBuiltinString_t (retain, @dynamic, nonatomic)
     */
    public $errMsg;

    public function __construct()
    {
        $this->classInfo = new \wechat\PBClassInfo();

        $this->classInfo->nameProperty[] = 'ret';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0x1, 0x2, 0x5, 0x0, 0x0, '');

        $this->classInfo->nameProperty[] = 'errMsg';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0x2, 0x2, 0xB, 0x0, 0x0, 'SKBuiltinString_t');

        parent::__construct();
    }
}
