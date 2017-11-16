<?php

namespace wechat;

/**
 * Class LargeInteger
 * Structure to handle longlong
 * @package wechat
 */
class LargeInteger
{
    /**
     * @var integer 4 byte int (low)
     */
    public $intLow;
    /**
     * @var integer 4 byte int (high)
     */
    public $intHigh;
}