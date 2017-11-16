<?php

namespace Mdanter\Ecc\Serializer\Point;

use Mdanter\Ecc\Primitives\CurveFpInterface;
use Mdanter\Ecc\Primitives\PointInterface;

interface PointSerializerInterface
{
    /**
     *
     * @param  PointInterface $point
     * @return string
     */
    public function serialize(PointInterface $point);

    /**
     * @param  CurveFpInterface $curve Curve that contains the serialized point
     * @param  string $string
     * @return PointInterface
     */
    public function unserialize(CurveFpInterface $curve, $string);
}
