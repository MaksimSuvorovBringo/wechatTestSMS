<?php

namespace wechat\Object;

class NetworkControl extends \wechat\WXPBGeneratedMessage
{
    /**
     * @var string (retain, @dynamic,    nonatomic)
     */
    public $portList;

    /**
     * @var string (retain, @dynamic, nonatomic)
     */
    public $timeoutList;

    /**
     * @var integer (@dynamic, nonatomic) unsigned
     */
    public $minNoopInterval;

    /**
     * @var integer (@dynamic, nonatomic) unsigned
     */
    public $maxNoopInterval;

    /**
     * @var integer (@dynamic, nonatomic)
     */
    public $typingInterval;

    /**
     * @var integer (@dynamic, nonatomic)
     */
    public $noopIntervalTime;

    public function __construct()
    {
        $this->classInfo = new \wechat\PBClassInfo();

        $this->classInfo->nameProperty[] = 'portList';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0x1, 0x1, 0x9, 0x0, 0x0, '');

        $this->classInfo->nameProperty[] = 'timeoutList';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0x2, 0x1, 0x9, 0x0, 0x0, '');

        $this->classInfo->nameProperty[] = 'minNoopInterval';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0x3, 0x1, 0xD, 0x0, 0x0, '');

        $this->classInfo->nameProperty[] = 'maxNoopInterval';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0x4, 0x1, 0xD, 0x0, 0x0, '');

        $this->classInfo->nameProperty[] = 'typingInterval';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0x5, 0x1, 0x5, 0x0, 0x0, '');

        $this->classInfo->nameProperty[] = 'noopIntervalTime';
        $this->classInfo->objectDefinition[] = new \wechat\ObjectDefinition(0x7, 0x1, 0x5, 0x0, 0x0, '');

        parent::__construct();
    }
}