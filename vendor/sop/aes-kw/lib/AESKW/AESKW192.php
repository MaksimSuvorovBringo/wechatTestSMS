<?php

namespace AESKW;


/**
 * Implements AES key wrap with 192 bit key size.
 */
class AESKW192 extends Algorithm
{
    protected function _cipherMethod()
    {
        return "AES-192-ECB";
    }

    protected function _keySize()
    {
        return 24;
    }
}
