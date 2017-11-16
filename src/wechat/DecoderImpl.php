<?php

namespace wechat;

class DecoderImpl
{
    /**
     * @var Decoder
     */
    public $Decoder;
    /**
     * @var DecoderEx
     */
    public $DecoderEx;
    public $sizeCache = [];

    /**
     * DecoderImpl constructor.
     * @param string $pNsData data buffer stream
     */
    public function __construct($pNsData)
    {
        $this->Decoder = new Decoder();
        $this->DecoderEx = new DecoderEx();

        $this->Decoder->pBuffer = $pNsData;

        $this->Decoder->Size = new LargeInteger();
        $this->Decoder->Size->intLow = strlen($this->Decoder->pBuffer);
        $this->Decoder->Size->intHigh = 0;

        $this->Decoder->ReadOffset = 0;
        $this->Decoder->__0OnInit = 0;
        $this->Decoder->MaxAllowedStreamSize_0x7FFFFFFF_OrEndOfStream = 0x7FFFFFFF;

        $this->DecoderEx->DeepOfChildInheritance = 0;
        $this->DecoderEx->MaxAllowedDeepOfChildInheritance_64 = 64;
        $this->DecoderEx->_0x4000000OnInit = 0x4000000;
    }

    /**
     * @param WXPBGeneratedMessage $pObject
     * @throws \Exception
     */
    public function DecodeObjectRecursively($pObject)
    {
        $objectSize = $this->Decoder->getRecordLength();
        if ($this->DecoderEx->DeepOfChildInheritance >= $this->DecoderEx->MaxAllowedDeepOfChildInheritance_64) {
            throw new \UnexpectedValueException('Recursion limit exceed');
        }

        $this->sizeCache[] = [$this->Decoder->Size->intLow, $this->Decoder->Size->intHigh];
        $streamSize = $this->Decoder->checkAndGetObjectStreamSize($objectSize);
        $this->DecoderEx->DeepOfChildInheritance++;
        $pObject->mergeFromCodedInputData($this);
        $this->Decoder->CheckAndThrowDecoderException(0);
        $this->Decoder->MaxAllowedStreamSize_0x7FFFFFFF_OrEndOfStream = $streamSize;
        $this->DecoderEx->DeepOfChildInheritance--;

        $realSize = $this->Decoder->Size->intLow + ($this->Decoder->Size->intHigh << 32);
        $this->Decoder->Size->intLow = $realSize;
        if ($realSize <= $streamSize) {
            $this->Decoder->Size->intHigh = 0;
        } else {
            $this->Decoder->Size->intLow = $streamSize;
            $this->Decoder->Size->intHigh = $realSize - $streamSize;
        }
        $fix = array_pop($this->sizeCache);
        $this->Decoder->Size->intLow = $fix[0];
        $this->Decoder->Size->intHigh = $fix[1];
    }

    public function shiftToNextObject($objStreamSize)
    {
        $objectStreamSize = new LargeInteger();
        $objectStreamSize->intLow = $objStreamSize;
        $this->Decoder->MaxAllowedStreamSize_0x7FFFFFFF_OrEndOfStream = $objStreamSize;
        $amountSize = ($this->Decoder->Size->intHigh >> 32) + $this->Decoder->Size->intLow;
        $this->Decoder->Size->intLow = $amountSize;
        if ($amountSize > $objectStreamSize->intLow) {
            $objectStreamSize->intHigh = $amountSize - $objectStreamSize->intLow;
            $this->Decoder->Size = $objectStreamSize;
        } else {
            $this->Decoder->Size->intHigh = 0;
        }
    }
}
