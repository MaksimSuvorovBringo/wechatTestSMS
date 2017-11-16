<?php

namespace wechat;

/**
 * Class Decoder
 * @package wechat
 */
class Decoder
{
    /**
     * @var string Input data
     */
    public $pBuffer;
    /**
     * @var LargeInteger length of input data
     */
    public $Size;
    /**
     * @var integer Current byte
     */
    public $ReadOffset;
    /**
     * @var integer 1 if class initialized otherwise 0
     */
    public $__0OnInit;
    /**
     * @var integer maximum allowed stream size
     */
    public $MaxAllowedStreamSize_0x7FFFFFFF_OrEndOfStream;

    /**
     * Read integer from buffer, if reading first integer, then we have "Object Type"
     * @return int
     * @throws \Exception
     */
    public function getObjectType()
    {
        if ($this->ReadOffset === $this->Size->intLow) {
            $this->__0OnInit = 0;
            $result = 0;
        } else {
            $result = $this->getRecordLength();
        }
        return $result;
    }

    /**
     * Read 7-bit integer from buffer
     * @return int integer
     * @throws \Exception
     */
    public function getRecordLength()
    {
        $firstCharAndResult = $this->getNextSignedByte();
        if ($firstCharAndResult < 0) {
            $firstMsb = $firstCharAndResult & 0x7F;
            $secondChar = $this->getNextSignedByte();
            $secondShl7 = $secondChar << 7;
            if ($secondChar < 0) {
                $firstMsb |= $secondShl7 & 0x3F80;
                $thirdChar = $this->getNextSignedByte();
                $secondShl7 = $thirdChar << 14;
                if ($thirdChar < 0) {
                    $firstMsb |= $secondShl7 & 0x1FC000;
                    $thirdCharAlt = $this->getNextSignedByte();
                    $secondShl7 = $thirdCharAlt << 21;
                    if ($thirdCharAlt < 0) {
                        $v9 = $firstMsb | (($thirdCharAlt << 21) & 0xFE00000);
                        $v10 = $this->getNextSignedByte();
                        $firstCharAndResult = $v9 | ($v10 << 28);
                        if ($v10 < 0) {
                            $counter = 0;
                            while ($this->getNextSignedByte() < 0) {
                                if (++$counter > 4) {
                                    throw new \UnexpectedValueException('Invalid protocol buffer');
                                }
                            }
                        }
                    } else {
                        $firstCharAndResult = $firstMsb | $secondShl7;
                    }
                } else {
                    $firstCharAndResult = $firstMsb | $secondShl7;
                }
            } else {
                $firstCharAndResult = $firstMsb | $secondShl7;
            }
        }
        return $firstCharAndResult;
    }

    /**
     * Get next byte as signed integer
     * @return integer
     * @throws \Exception
     */
    public function getNextSignedByte()
    {
        $result = unpack('cint', $this->getNextByte());
        return $result['int'];
    }

    /**
     * Get next byte from buffer
     * @return string next byte
     * @throws \Exception if buffer end
     */
    public function getNextByte()
    {
        if ($this->ReadOffset === $this->Size->intLow) {
            throw new \UnexpectedValueException(strtr('reach end, bufferPos: :pos bufferSize: :size', [
                ':pos' => $this->ReadOffset,
                ':size' => $this->Size->intLow,
            ]));
        }
        return $this->pBuffer[$this->ReadOffset++];
    }

    public function checkObjectType($objectType)
    {
        if (($objectType & 0x07) <= 5) {
            switch ($objectType & 0x07) {
                case 0:
                    $this->getRecordLength();
                    return 1;
                    break;
                case 1:
                    $this->decodeInt64();
                    return 1;
                    break;
                case 2:
                    $recordLength = $this->getRecordLength();
                    $this->checkMaxObjectSize($recordLength);
                    return 1;
                    break;
                case 4:
                    return 0;
                    break;
                case 5:
                    $this->decodeInt32();
                    return 1;
                    break;
                default:
                    break;
            }
        }
        throw new \InvalidArgumentException('Invalid Wire Type');
    }

    /**
     * Read int64 from buffer
     * @return int
     * @throws \Exception
     */
    public function decodeInt64()
    {
        $itByte = $this->getNextUnsignedByte();
        $v3 = $itByte | ($this->getNextUnsignedByte() << 8);
        $v4 = $v3 | ($this->getNextUnsignedByte() << 16);
        $resultLow = $v4 | ($this->getNextUnsignedByte() << 24);
        $v6 = $this->getNextUnsignedByte();
        $v7 = $v6 | ($this->getNextUnsignedByte() << 8);
        $v8 = $v7 | ($this->getNextUnsignedByte() << 16);
        $resultHigh = $v8 | ($this->getNextUnsignedByte() << 24);
        return $resultHigh << 32 | $resultLow;
    }

    /**
     * Get next byte as unsigned integer
     * @return integer
     * @throws \Exception
     */
    public function getNextUnsignedByte()
    {
        $result = unpack('Cint', $this->getNextByte());
        return $result['int'];
    }

    /**
     * Check if object size in buffer is corrent
     * @param int $recordLength
     * @throws \Exception
     */
    public function checkMaxObjectSize($recordLength)
    {
        if ($recordLength < 0) {
            throw new \UnexpectedValueException('negative size');
        }
        $readOffset = $this->ReadOffset;
        $maxAllowedMessageSize = $this->MaxAllowedStreamSize_0x7FFFFFFF_OrEndOfStream;
        if ($readOffset + $recordLength > $maxAllowedMessageSize) {
            $this->checkMaxObjectSize($maxAllowedMessageSize - $readOffset);
            throw new \UnexpectedValueException('truncated message');
        }
        if ($this->Size->intLow - $readOffset < $recordLength) {
            throw new \UnexpectedValueException('truncated message');
        }
        $this->ReadOffset = $readOffset + $recordLength;
    }

    /**
     * Read int32 from buffer
     * @return int
     * @throws \Exception
     */
    public function decodeInt32()
    {
        $v2 = $this->getNextUnsignedByte();
        $v3 = $v2 | ($this->getNextUnsignedByte() << 8);
        $v4 = $v3 | ($this->getNextUnsignedByte() << 16);
        return $v4 | ($this->getNextUnsignedByte() << 32);
    }

    /**
     * @param int $objectStreamSize
     * @return int max allowed size
     * @throws \Exception
     */
    public function checkAndGetObjectStreamSize($objectStreamSize)
    {
        if ($objectStreamSize < 0) {
            throw new \UnexpectedValueException('truncated message');
        }
        $maxAllowedSize = $this->MaxAllowedStreamSize_0x7FFFFFFF_OrEndOfStream;
        $amountSize = new LargeInteger();
        $amountSize->intLow = $this->ReadOffset + $objectStreamSize;
        if ($amountSize->intLow > $maxAllowedSize) {
            throw new \UnexpectedValueException('truncated message');
        }
        $this->MaxAllowedStreamSize_0x7FFFFFFF_OrEndOfStream = $amountSize->intLow;
        $realSize = ($this->Size->intHigh << 32) + $this->Size->intLow;
        $this->Size->intLow = $realSize;
        if ($realSize <= $amountSize->intLow) {
            $this->Size->intHigh = 0;
        } else {
            $amountSize->intHigh = $realSize - $amountSize->intLow;
            $this->Size = $amountSize;
        }
        return $maxAllowedSize;
    }

    public function CheckAndThrowDecoderException($sizeToCheck)
    {
        if ($this->__0OnInit !== $sizeToCheck) {
            throw new \UnexpectedValueException('Invalid end tag');
        }
    }

    public function DecodeNumberWithDouble()
    {
        return $this->decodeInt64();
    }

    public function DecodeNumberWithFloat()
    {
        return $this->decodeInt32();
    }

    public function DecodeVarInt()
    {
        $v2 = 0;
        $result = 0;
        $v4 = 0;
        while (1) {
            $itByte = $this->getNextSignedByte();
            $itVal = $itByte & 0x7F;
            $v7 = $itVal >> (32 - $v2);
            if ($v2 - 32 >= 0) {
                $v7 = $itVal << ($v2 - 32);
            }
            $v4 |= $v7;
            $result |= $itVal << 2;
            if (!($itByte & 0x80)) {
                break;
            }
            $v2 += 7;
            if ($v2 > 63) {
                throw new \UnexpectedValueException('Malformed varint');
            }
        }
        return ($result >> 32) | $v4;
    }

    public function DecodeInt32From8Bytes()
    {
        return $this->decodeInt64();
    }

    public function DecodeBoolFromByte()
    {
        return $this->getRecordLength() !== 0;
    }

    public function DecodeNSData()
    {
        /** @noinspection PhpExpressionResultUnusedInspection */

        return $this->DecodeString();
    }

    public function DecodeString()
    {
        $recordLength = $this->getRecordLength();
        $bufferSize = $this->Size->intLow;
        $readOffset = $this->ReadOffset;
        if ($recordLength < 1 || $recordLength > $bufferSize - $readOffset) {
            if ($recordLength) {
                throw new \UnexpectedValueException('invalid protocol buffer');
            } else {
                $result = '';
            }
        } else {
            $result = substr($this->pBuffer, $this->ReadOffset, $recordLength);
            $this->ReadOffset += $recordLength;
        }
        return $result;
    }

    public function getDecodedRecordLength()
    {
        $result = $this->getRecordLength();
        return -($result & 1) ^ ($result >> 1);
    }

    public function getStreamAmountSize()
    {
        $streamSize = $this->MaxAllowedStreamSize_0x7FFFFFFF_OrEndOfStream;
        if ($streamSize === 0x7FFFFFFF) {
            /** @noinspection OnlyWritesOnParameterInspection */
            /** @noinspection PhpUnusedLocalVariableInspection */
            $result = -1;
        } else {
            /** @noinspection OnlyWritesOnParameterInspection */
            /** @noinspection PhpUnusedLocalVariableInspection */
            $result = $streamSize - $this->ReadOffset;
        }
        return $streamSize;
    }

}