<?php

namespace AESKW;


/**
 * Implements AES key wrap with 256 bit key size.
 */
class AESKW256 extends Algorithm
{
    protected function _cipherMethod()
    {
        return "AES-256-ECB";
    }

    protected function _keySize()
    {
        return 32;
    }
}
