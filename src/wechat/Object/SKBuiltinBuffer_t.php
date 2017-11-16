<?php

namespace wechat\Object;

class SKBuiltinBuffer_t extends \wechat\WXPBGeneratedMessage
{
    /**
     * @var integer (@dynamic, nonatomic)    unsigned
     */
    public $iLen;

    /**
     * @var string (retain, @dynamic, nonatomic)
     */
    public $buffer;

    public function __construct($buf = '')
    {
        $this->classInfo = new \wechat\PBClassInfo();

        $this->classInfo->nameProperty[] = 'iLen';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0x1, 0x2, 0xD, 0x0, 0x0, '');

        $this->classInfo->nameProperty[] = 'buffer';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0x2, 0x1, 0xC, 0x0, 0x0, '');

        parent::__construct();
        $this->iLen = strlen($buf);
        if ($buf !== '') {

            $this->buffer = $buf;
        }
    }
}
