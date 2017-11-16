<?php
/**
 * Created by PhpStorm.
 * User: xray
 * Date: 10.01.17
 * Time: 17:55
 */

namespace wechat;


class Encoder
{
    public $pBuffer;
    public $encoderInfo;

    public function __construct()
    {
        $this->pBuffer = '';
        $this->encoderInfo = new EncoderInfo();
    }

    public function SerializeFieldType($fieldNumber, $fieldType)
    {
        $this->SerializeInt32($fieldNumber << 3 | $fieldType);

    }

    public function SerializeInt32($valueSize)
    {
        if ($valueSize > 0x7F) {
            do {
                $this->WriteByteToStream($valueSize | 0x80);
                $itByte = $valueSize >> 7;
                $v5 = $valueSize >= 0x4000;
                $valueSize >>= 7;
            } while ($v5);
        } else {
            $itByte = $valueSize;
        }
        $this->WriteByteToStream($itByte);
    }

    public function WriteByteToStream($value)
    {
        $this->pBuffer .= pack('C', $value);
        $this->encoderInfo->writeOffset++;
    }

    public function SerialDouble($value)
    {
        $this->SerializeUint64Value($value);
    }

    public function SerializeUint64Value(LargeInteger $value)
    {
        // verified  with debugger
        $this->WriteByteToStream(($value->intLow >> 0) & 0xFF);
        $this->WriteByteToStream(($value->intLow >> 8) & 0xFF);
        $this->WriteByteToStream(($value->intLow >> 16) & 0xFF);
        $this->WriteByteToStream(($value->intLow >> 24) & 0xFF);
        $this->WriteByteToStream(($value->intHigh >> 0) & 0xFF);
        $this->WriteByteToStream(($value->intHigh >> 8) & 0xFF);
        $this->WriteByteToStream(($value->intHigh >> 16) & 0xFF);
        $this->WriteByteToStream(($value->intHigh >> 24) & 0xFF);
    }

    public function SerializeFloat($value)
    {
        $this->SerializeUint32($value);
    }

    public function SerializeUint32($value)
    {
        $this->WriteByteToStream(($value >> 0) & 0xFF);
        $this->WriteByteToStream(($value >> 8) & 0xFF);
        $this->WriteByteToStream(($value >> 16) & 0xFF);
        $this->WriteByteToStream(($value >> 24) & 0xFF);
    }

    public function SerializeVarInt32(LargeInteger $value)
    {
        $this->SerializeVarInt($value);
    }

    public function SerializeVarInt(LargeInteger $value)
    {
        // verified  with debugger
        $v3 = 0;
        $intLow = $value->intLow;
        if ($value->intLow < 0x80) {
            $v3 = 1;
        }
        $intHigh = $value->intHigh;
        if ($value->intHigh) {
            $v3 = 0;
        }
        while (!$v3) {
            $this->WriteByteToStream(($intLow | 0x80) & 0xFF);
            $v3 = 0;
            if ($intLow < 0x4000) {
                $v3 = 1;
            }
            $intLow = ($intLow >> 7) | ($intHigh << 25);
            if ($intHigh) {
                $v3 = 0;
            }
            $intHigh >>= 7;
        }
        $this->WriteByteToStream($intLow);
    }

    public function SerializeUnsignedLongLong($value)
    {
        $this->SerializeVarInt($value);
    }

    public function SerializeVarIntOrInt32($value)
    {
        if ($value instanceof LargeInteger) {
            $value->intHigh = $value->intLow >> 31;
            $this->SerializeVarInt($value);
        } else {
            $this->SerializeInt32($value);
        }
    }

    public function SerializeBool($value)
    {
        $this->WriteByteToStream($value ? 1 : 0);
    }

    /**
     * @param WXPBGeneratedMessage $value
     * @param int $fieldType
     * @throws \Exception
     */
    public function serializeObjctRecursive($value, $fieldType)
    {
        /** @noinspection PhpExpressionResultUnusedInspection */
        $fieldType;
        $beforeSerialize = $this->encoderInfo->writeOffset;
        $value->writeToCodedOutputData($this);
        $afterSerialize = $this->encoderInfo->writeOffset;
        $serializedSize = $afterSerialize - $beforeSerialize;
        $split1 = substr($this->pBuffer, 0, $beforeSerialize);
        $split2 = substr($this->pBuffer, $beforeSerialize);
        $this->pBuffer = $split1;
        $this->SerializeInt32($serializedSize);
        $this->pBuffer .= $split2;
    }

    public function SerializeNSData($value)
    {
        $this->SerializeNSString($value);
    }

    public function SerializeNSString($value)
    {
        $this->SerializeInt32(strlen($value));
        $this->pBuffer .= $value;
        $this->encoderInfo->writeOffset += strlen($value);
    }

    public function SerializeRecordLength($value)
    {
        $encodedValue = $this->SerializeEncodedRecordLength($value);
        $this->SerializeInt32($encodedValue);
    }

    public function SerializeEncodedRecordLength($value)
    {
        return 2 * $value ^ ($value >> 31);
    }

    public function Serialize64BitRecordLength(LargeInteger $value)
    {
        $low32 = $this->Encode64BitValue($value->intLow, $value->intHigh);
        $encodedValue = new LargeInteger();
        $encodedValue->intLow = $low32;
        $encodedValue->intHigh = $value->intHigh;
        $this->SerializeVarInt($encodedValue);
    }

    public function Encode64BitValue($intLow, $intHigh)
    {
        return (2 * $intHigh ^ ($intLow >> 31));
    }

}