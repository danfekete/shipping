<?php
/**
 * Copyright (c) 2016, VOOV LLC.
 * All rights reserved.
 * Written by Daniel Fekete
 * daniel.fekete@voov.hu
 */

namespace Webshop\Components\Shipping\Contracts;


interface LabelInterface
{
    const NONE = 0;
    const A4 = 1;
    const A6 = 2;
    const PREPRINT = 3;

    /**
     * Return label type
     * @return int
     */
    public function getType();

    /**
     * Set label type
     * @param int $type
     * @return mixed
     */
    public function setType($type);
}