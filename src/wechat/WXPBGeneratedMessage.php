<?php

namespace wechat;

/**
 * Class WXPBGeneratedMessage
 * @package wechat
 */
abstract class WXPBGeneratedMessage
{
    /**
     * @var array used only with serialization
     */
    public $has_bits;
    /**
     * @var integer used only with serialization
     */
    public $serializedSize = -1;
    /**
     * @var PBClassInfo class info
     */
    public $classInfo;
    /**
     * @var integer pointer to dictionary, used only with serialization
     */
    public $ivarValueDict;

    public function __construct()
    {

        $this->classInfo->countProperty = count($this->classInfo->nameProperty);

        if ($this->classInfo->countProperty >= 0x61) {
            throw new \UnexpectedValueException('PBFields exceed');
        }
    }

    public function dump($offset = 2)
    {
//   return 1;
        print get_class($this) . "::\n";
        foreach ($this->classInfo->nameProperty as $propertyName) {
            if (is_array($this->$propertyName)) {
                /** @noinspection ForeachSourceInspection */
                foreach ($this->$propertyName as $k => $propertyOne) {
                    /** @noinspection DisconnectedForeachInstructionInspection */
                    print str_repeat('-', $offset);
                    print "{$propertyName}[{$k}] = ";
                    if (is_object($this->$propertyName[$k])) {
                        $this->$propertyName[$k]->dump($offset + 2);
                    } else {
                        if ($this instanceof \wechat\Object\SKBuiltinBuffer_t && $propertyName === 'buffer') {
                            //print 'NOT HEX: ' . /*bin2hex*/
                            //$this->$propertyName[$k] . "\n";
                            print 'HEX: ' . bin2hex($this->$propertyName[$k]) . "\n";
                        } else {
                            print $this->$propertyName[$k] . "\n";
                        }
                    }
                }
                continue;
            } else {
                print str_repeat('-', $offset);
                print "{$propertyName} = ";
            }
            if (is_object($this->$propertyName)) {
                $this->$propertyName->dump($offset + 2);
            } else {
                if ($this instanceof \wechat\Object\SKBuiltinBuffer_t && $propertyName === 'buffer') {
                    //print 'NOT HEX: ' . /*bin2hex*/
                    //$this->$propertyName . "\n";
                    print 'HEX: ' . bin2hex($this->$propertyName) . "\n";
                } else {
                    print $this->$propertyName . "\n";
                }
            }
        }
    }

    /**
     * Parse input buffer to object
     * @param string $nsData input buffer
     * @throws \Exception
     */
    public function mergeFromData($nsData)
    {
        $decoderImpl = new DecoderImpl($nsData);
        $this->mergeFromCodedInputData($decoderImpl);
        if (defined('IS_DEBUG') && IS_DEBUG) {
            if (
                substr(get_class($this), -strlen('Response')) === 'Response'
                || substr(get_class($this), -strlen('Res')) === 'Res'
            ) {
                print '<---- ' . preg_replace('#.+\\\\#', '', get_class($this)) . "\n";
                if (isset($this->baseResponse) && $this->baseResponse->ret !== 0) {
                    print "Code: {$this->baseResponse->ret}, Message: {$this->baseResponse->errMsg->string}\n";
                }
            }
        }
    }

    /**
     * @param DecoderImpl $decoderImpl
     * @throws \Exception
     * @return self
     */
    public function mergeFromCodedInputData($decoderImpl)
    {
        $objectType = $decoderImpl->Decoder->getObjectType();
        if (!$objectType) {
            return $this;
        }
        LOOP_START:
        if ($this->classInfo->countProperty) {
            $pObjectDefinitionOffset = 0;
            do {
                if ($this->classInfo->objectDefinition[$pObjectDefinitionOffset]->FieldNumber === $objectType >> 3) {
                    break;
                }
                $pObjectDefinitionOffset++;
            } while ($pObjectDefinitionOffset < $this->classInfo->countProperty);
        } else {
            $pObjectDefinitionOffset = 0;
        }
        if ($pObjectDefinitionOffset === $this->classInfo->countProperty) {
            if (!$decoderImpl->Decoder->checkObjectType($objectType)) {
                return $this;
            }
            goto OBJECT_SIZE_CHECK_SUCCESS;
        }
        $pParentObjDefinition = $this->classInfo->objectDefinition;
        $pChildObjectImpl = $pParentObjDefinition[$pObjectDefinitionOffset];
        $fieldType = $pChildObjectImpl->FieldType;
        if ($fieldType === 0x0B) {
            switch (true) {
                case substr($pChildObjectImpl->pNSObjectName, -strlen('Response')) === 'Response':
                    $namespace = 'Response';
                    break;
                case substr($pChildObjectImpl->pNSObjectName, -strlen('Request')) === 'Request':
                    $namespace = 'Request';
                    break;
                default:
                    $namespace = 'Object';
                    break;
            }
            $className = ("\\wechat\\{$namespace}\\" . $pChildObjectImpl->pNSObjectName);
            $pDecodedObject = new $className;

            $decoderImpl->DecodeObjectRecursively($pDecodedObject);
            if ($pChildObjectImpl->IsArray === 3) {
                $this->pushProperty($pObjectDefinitionOffset, $pDecodedObject);
            } else {
                $this->setProperty($pObjectDefinitionOffset, $pDecodedObject);
            }
            goto CONTINUE_DECODE;
        }
        if ($fieldType === 0x0A) {
            throw new \UnexpectedValueException('Unsupported field type');
        }
        if ($pChildObjectImpl->IsArray !== 3) {
            $pBaseTypeObj = $this->readValueFromCodedInputData($decoderImpl, $fieldType);
            if ($fieldType === 0x0E) {
                /** @noinspection OnlyWritesOnParameterInspection */
                /** @noinspection PhpUnusedLocalVariableInspection */
                $pObj = $pChildObjectImpl->pNSObjectName;
                /** @noinspection OnlyWritesOnParameterInspection */
                /** @noinspection PhpUnusedLocalVariableInspection */
                $intval = (int)$pBaseTypeObj;
                /** @noinspection NotOptimalIfConditionsInspection */
                if (true /*unknown condition*/) {
                    throw new \UnexpectedValueException('Unknown enum value');
                }
            }
            $this->setProperty($pObjectDefinitionOffset, $pBaseTypeObj);
            goto CONTINUE_DECODE;
        }

        if (!$pChildObjectImpl->IsNotBaseTypeObject) {
            $pDecodedSimpleObj = $this->readValueFromCodedInputData($decoderImpl, $fieldType);
            if ($fieldType === 0x0E) {
                /** @noinspection OnlyWritesOnParameterInspection */
                /** @noinspection PhpUnusedLocalVariableInspection */
                $pObj = $pChildObjectImpl->pNSObjectName;
                /** @noinspection OnlyWritesOnParameterInspection */
                /** @noinspection PhpUnusedLocalVariableInspection */
                $intval = (int)$pDecodedSimpleObj;
                /** @noinspection NotOptimalIfConditionsInspection */
                if (true /*unknown condition*/) {
                    throw new \UnexpectedValueException('Unknown enum value');
                }
            }
            $this->pushProperty($pObjectDefinitionOffset, $pDecodedSimpleObj);
            goto CONTINUE_DECODE;
        }
        $nextRecordLength = $decoderImpl->Decoder->getRecordLength();
        $objStreamSize = $decoderImpl->Decoder->checkAndGetObjectStreamSize($nextRecordLength);
        if ($decoderImpl->Decoder->getStreamAmountSize() >= 1) {
            GOTO LOOP_END;
        }
        DECODE_NEXT_OBJECT:
        $decoderImpl->shiftToNextObject($objStreamSize);
        CONTINUE_DECODE:

        OBJECT_SIZE_CHECK_SUCCESS:
        $objectType = $decoderImpl->Decoder->getObjectType();
        if (!$objectType) {
            return $this;
        }
        GOTO LOOP_START;
        LOOP_END:
        while (1) {
            $pSimpleObject = $this->readValueFromCodedInputData($decoderImpl, $fieldType);
            if ($fieldType === 0x0E) {
                /** @noinspection OnlyWritesOnParameterInspection */
                /** @noinspection PhpUnusedLocalVariableInspection */
                $pObj = $pChildObjectImpl->pNSObjectName;
                /** @noinspection OnlyWritesOnParameterInspection */
                /** @noinspection PhpUnusedLocalVariableInspection */
                $intval = (int)$pSimpleObject;
                /** @noinspection NotOptimalIfConditionsInspection */
                if (true /*unknown condition*/) {
                    throw new \UnexpectedValueException('Unknown enum value');
                    break;
                }
            }
            $this->pushProperty($pObjectDefinitionOffset, $pSimpleObject);
            if ($decoderImpl->Decoder->getStreamAmountSize() <= 0) {
                goto DECODE_NEXT_OBJECT;
            }
        }
        return $this;
    }

    public function pushProperty($fieldNumber, $value)
    {
        $fieldName = $this->classInfo->nameProperty[$fieldNumber];
        $this->{$fieldName}[] = $value;
//        $this->ivarValueDict[$fieldNumber] = $value;
//        $this->has_bits[$fieldNumber/32] = ($this->has_bits[$fieldNumber/32]) | 1 << ($fieldNumber & 0x1F);
    }

    public function setProperty($fieldNumber, $value)
    {
        $fieldName = $this->classInfo->nameProperty[$fieldNumber];
        $this->$fieldName = $value;
//        $this->ivarValueDict[$fieldNumber] = $value;
//        $this->has_bits[$fieldNumber/32] = ($this->has_bits[$fieldNumber/32]) | 1 << ($fieldNumber & 0x1F);
    }

    /**
     * @param DecoderImpl $decoderImpl
     * @param int $fieldType
     * @return mixed
     * @throws \Exception
     */
    public function readValueFromCodedInputData($decoderImpl, $fieldType)
    {
        $fieldType--;
        if ($fieldType > 0x11) {
            throw new \UnexpectedValueException('Unsupported type');
        }
        switch ($fieldType) {
            case 0x00:
                $result = $decoderImpl->Decoder->DecodeNumberWithDouble();
                break;
            case 0x01:
                $result = $decoderImpl->Decoder->DecodeNumberWithFloat();
                break;
            case 0x02:
                $result = $decoderImpl->Decoder->DecodeVarInt();
                break;
            case 0x03:
                $result = $decoderImpl->Decoder->DecodeVarInt();
                break;
            case 0x04:
                $result = $decoderImpl->Decoder->getRecordLength();
                break;
            case 0x05:
                $result = $decoderImpl->Decoder->DecodeInt32From8Bytes();
                break;
            case 0x06:
                $result = $decoderImpl->Decoder->decodeInt32();
                break;
            case 0x07:
                $result = $decoderImpl->Decoder->DecodeBoolFromByte();
                break;
            case 0x08:
                $result = $decoderImpl->Decoder->DecodeString();
                break;
            case 0x0B:
                $result = $decoderImpl->Decoder->DecodeNSData($fieldType);
                break;
            case 0x0C:
                $result = $decoderImpl->Decoder->getRecordLength();
                break;
            case 0x0D:
                $result = $decoderImpl->Decoder->getRecordLength();
                break;
            case 0x0E:
                $result = $decoderImpl->Decoder->decodeInt32();
                break;
            case 0x0F:
                $result = $decoderImpl->Decoder->DecodeInt32From8Bytes();
                break;
            case 0x10:
                $result = $decoderImpl->Decoder->getDecodedRecordLength();
                break;
            default:
                throw new \UnexpectedValueException('Unsupported type');
                break;
        }
        return $result;
    }

    public function setValueAtIndex($value, $index)
    {
        $isOk = 0;
        foreach ($this->classInfo->nameProperty as $propertyNumber => $propertyName) {
            if ($propertyName === $index) {
                $this->ivarValueDict[$propertyNumber] = $value;
                $isOk = 1;
            }
        }
        if (!$isOk) {
            throw new \UnexpectedValueException('index not found');
        }
    }

    public function getValueAtIndex($index)
    {
        foreach ($this->classInfo->nameProperty as $propertyNumber => $propertyName) {
            if ($propertyName === $index) {
                $value = $this->ivarValueDict[$propertyNumber];
            }
        }
        /** @noinspection UnSafeIsSetOverArrayInspection */
        if (!isset($value)) {
            throw new \UnexpectedValueException('Value not found by index');
        }
        return $value;
    }

    public function serializedData()
    {
        if (defined('IS_DEBUG') && IS_DEBUG) {

            if (
                substr(get_class($this), -strlen('Request')) === 'Request'
                || substr(get_class($this), -strlen('Req')) === 'Req'
            ) {
                print '----> ' . preg_replace('#.+\\\\#', '', get_class($this)) . "\n";
            }
        }
        $encoder = new \wechat\Encoder();
        $this->writeToCodedOutputData($encoder);
        return $encoder->pBuffer;
    }

    /**
     * @param Encoder $encoder
     * @throws \Exception
     */
    public function writeToCodedOutputData($encoder)
    {
        $classInfo = $this->classInfo;
        if ($classInfo->countProperty) {
            $counter_property = 0;
            /** @noinspection OnlyWritesOnParameterInspection */
            /** @noinspection PhpUnusedLocalVariableInspection */
            $hasBits = $this->has_bits;
            do {
                $pItObjectDefinition = $classInfo->objectDefinition[$counter_property];
                if ($pItObjectDefinition->IsArray === 3) {
                    $pNsMutableArrayObject = $this->{$classInfo->nameProperty[$counter_property]};
                    $fieldType = $pItObjectDefinition->FieldType;
                    if ($fieldType === 0x0B) {
                        $numberOfObjectsNSUInteger = count($pNsMutableArrayObject);
                        if ($numberOfObjectsNSUInteger > 0) {
                            $processedElementsCount = 0;
                            do {
                                $this->writeValueToCodedOutputData($encoder, $pNsMutableArrayObject[$processedElementsCount++], $pItObjectDefinition->FieldNumber, $pItObjectDefinition->FieldType);
                            } while ($processedElementsCount < $numberOfObjectsNSUInteger);
                        }
                    } else {
                        if ($fieldType === 0x0A) {
                            throw new \UnexpectedValueException('Unsupported field type');
                        }
                        if ($pItObjectDefinition->IsNotBaseTypeObject) {
                            if (count($pNsMutableArrayObject)) {
                                if ($pItObjectDefinition->IsNotBaseTypeObject) {
                                    $serializedFieldType = 2;
                                } else {
                                    $gFieldTypeToSerializedFialdTypeDictionary = [0xFFFFFFFF, 1, 5, 0, 0, 0, 1, 5, 0, 2, 3, 2, 2, 0, 0, 5, 1, 0, 0];
                                    $serializedFieldType = $gFieldTypeToSerializedFialdTypeDictionary[$fieldType];
                                }
                                $encoder->SerializeInt32($serializedFieldType | 8 * $pItObjectDefinition->FieldNumber);
                                $pPropertyName = $this->classInfo->nameProperty[$counter_property];
                                $pPropertyObject = $this->{$pPropertyName};
                                $objectsCount = count($pPropertyObject);
                                $encoder->SerializeInt32($objectsCount);
                            }
                            $numberOfObjectsNSUInteger = count($pNsMutableArrayObject);
                            if ($numberOfObjectsNSUInteger) {
                                $mutationsCount = 0;
                                do {
                                    $this->writeValueToCodedOutputDataNoTag($encoder, $pNsMutableArrayObject[$mutationsCount++], $fieldType);
                                } while ($mutationsCount < $numberOfObjectsNSUInteger);
                            }
                        } else {
                            $pValueObj = $this->{$classInfo->nameProperty[$counter_property]};
                            $numberOfObjectsNSUInteger_2 = count($pValueObj);
                            $mutationCount = 0;
                            do {
                                $this->writeValueToCodedOutputData($encoder, $pValueObj[$mutationCount++], $pItObjectDefinition->FieldNumber, $pItObjectDefinition->FieldType);
                            } while ($mutationCount < $numberOfObjectsNSUInteger_2);
                        }
                    }
                } else {
                    $value = $this->{$classInfo->nameProperty[$counter_property]};
                    if (null !== $value) {
                        $this->writeValueToCodedOutputData($encoder, $value, $pItObjectDefinition->FieldNumber, $pItObjectDefinition->FieldType);
                    }
                }
                $counter_property++;

            } while ($counter_property < $classInfo->countProperty);
        }
    }/** @noinspection MoreThanThreeArgumentsInspection */

    /**
     * @param Encoder $encoder
     * @param $value
     * @param $fieldNumber
     * @param $fieldType
     * @throws \Exception
     */
    public function writeValueToCodedOutputData($encoder, $value, $fieldNumber, $fieldType)
    {
        $FieldTypeToSerializedFialdTypeDictionary = array(
            0xFFFFFFFF, 1, 5, 0, 0, 0, 1, 5, 0, 2, 3, 2, 2, 0, 0, 5, 1, 0, 0
        );
        $encoder->SerializeFieldType($fieldNumber, $FieldTypeToSerializedFialdTypeDictionary[$fieldType]);
        $this->writeValueToCodedOutputDataNoTag($encoder, $value, $fieldType);

    }

    /**
     * @param Encoder $encoder
     * @param mixed $value
     * @param int $fieldType
     * @throws \Exception
     */
    public function writeValueToCodedOutputDataNoTag($encoder, $value, $fieldType)
    {
        if ($fieldType - 1 > 0x11) {
            throw new \UnexpectedValueException('Unsupported type');
        }
        switch ($fieldType) {
            case 0x01:
                $encoder->SerialDouble($value);
                break;
            case 0x02:
                $encoder->SerializeFloat($value);
                break;
            case 0x03:
                $encoder->SerializeVarInt32($value);
                break;
            case 0x04:
                $encoder->SerializeUnsignedLongLong($value);
                break;
            case 0x05:
                $encoder->SerializeVarIntOrInt32($value);
                break;
            case 0x06:
                $encoder->SerializeUint64Value($value);
                break;
            case 0x07:
                $encoder->SerializeUint32($value);
                break;
            case 0x08:
                $encoder->SerializeBool($value);
                break;
            case 0x09:
                $encoder->SerializeNSString($value);
                break;
            default:
                throw new \UnexpectedValueException('Unsupported type');
                break;
            case 0x0B:
                $encoder->serializeObjctRecursive($value, $fieldType);
                break;
            case 0x0C:
                $encoder->SerializeNSData($value);
                break;
            case 0x0D:
                $encoder->SerializeInt32($value);
                break;
            case 0x0E:
                $encoder->SerializeInt32($value);
                break;
            case 0x0F:
                $encoder->SerializeUint32($value);
                break;
            case 0x10:
                $encoder->SerializeUint64Value($value);
                break;
            case 0x11:
                $encoder->SerializeRecordLength($value);
                break;
            case 0x12:
                $encoder->Serialize64BitRecordLength($value);
                break;
        }
    }
}