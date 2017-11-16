<?php
require_once dirname(__FILE__) . '/vendor/autoload.php';

function imeiRandom()
{
    $code = intRandom(14);
    $position = 0;
    $total = 0;
    while ($position < 14) {
        if ($position % 2 == 0) {
            $prod = 1;
        } else {
            $prod = 2;
        }
        $actualNum = $prod * $code[$position];
        if ($actualNum > 9) {
            $strNum = strval($actualNum);
            $total += $strNum[0] + $strNum[1];
        } else {
            $total += $actualNum;
        }
        $position++;
    }
    $last = 10 - ($total % 10);
    if ($last == 10) {
        $imei = $code . 0;
    } else {
        $imei = $code . $last;
    }
    return $imei;
}

/**
 * @param int $size
 * @return $int
 */
function intRandom($size)
{
    $validCharacters = utf8_decode("0123456789");
    $validCharNumber = strlen($validCharacters);
    $int = '';
    while (strlen($int) < $size) {
        $index = mt_rand(0, $validCharNumber - 1);
        $int .= $validCharacters[$index];
    }
    return $int;
}

function md5ToUUID($md5)
{
    $result = preg_replace('#(.{8})(.{4})(.{4})(.{4})(.{12})#', '\\1-\\2-\\3-\\4-\\5', $md5);
    $result = strtoupper($result);
    return $result;
}

function md5ToMac($md5)
{
    $result = preg_replace('#(.{2})(.{2})(.{2})(.{2})(.{2})(.{2})#', '\\1:\\2:\\3:\\4:\\5:\\6', substr($md5, 0, 12));

    return $result;
}



function deleteHeaderFromResponse($response)
{
    $response = substr($response, ord($response[1]) >> 2 );
    return $response;
}




