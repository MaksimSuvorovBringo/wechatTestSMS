<?php
/**
 * Created by PhpStorm.
 * User: xray
 * Date: 10.01.17
 * Time: 17:56
 */

namespace wechat;


class EncoderInfo
{
    public $bufferSize;
    public $writeOffset;

    public function __construct()
    {
        $this->writeOffset = 0;
    }


}