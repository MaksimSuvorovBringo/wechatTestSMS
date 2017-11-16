<?php

namespace wechat;

class PBClassInfo
{
    /**
     * @var integer number of object properties
     */
    public $countProperty;
    /**
     * @var array [$countProperty]
     */
    public $nameProperty;

    /**
     * @var array
     */
    public $field_8;
    /**
     * @var array
     */
    public $field_C;
    /**
     * @var ObjectDefinition[] instance of ObjectDefinition
     */
    public $objectDefinition;

    public function __construct()
    {
        $this->nameProperty = [];
        $this->objectDefinition = [];
    }
}