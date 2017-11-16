<?php

namespace wechat;
class ObjectDefinition
{
    /**
     * @var integer serial number of property (1 byte)
     */
    public $FieldNumber;
    /**
     * @var integer if property is array (1 byte)
     */
    public $IsArray;
    /**
     * @var integer type of property (1 byte)
     */
    public $FieldType;
    /**
     * @var integer if property is not base object (like string, number, etc, 1 byte)
     */
    public $IsNotBaseTypeObject;
    /**
     * @var string name of object (p - pointer)
     */
    public $pNSObjectName;

    public function __construct($FieldNumber, $IsArray, $FieldType, $IsNotBaseTypeObject, $field_4, $pNSObjectName)
    {
        $this->FieldNumber = $FieldNumber;
        $this->IsArray = $IsArray;
        $this->FieldType = $FieldType;
        $this->IsNotBaseTypeObject = $IsNotBaseTypeObject;
        $this->field_4 = $field_4;
        $this->pNSObjectName = $pNSObjectName;
    }
}