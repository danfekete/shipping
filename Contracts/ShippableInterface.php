<?php
/**
 * Created by PhpStorm.
 * User: dande
 * Date: 22/09/15
 * Time: 00:02
 */

namespace Webshop\Components\Shipping\Contracts;


interface ShippableInterface {

    /**
     * Get weight
     * @param null $unit
     * @return mixed
     */
    public function getWeight($unit = null);

    /**
     * Get width
     * @param null $unit
     * @return mixed
     */
    public function getWidth($unit = null);

    /**
     * Get height
     * @param null $unit
     * @return mixed
     */
    public function getHeight($unit = null);

    /**
     * Get depth
     * @param null $unit
     * @return mixed
     */
    public function getDepth($unit = null);

    /**
     * Get total volume
     * @param null $unit
     * @return mixed
     */
    public function getVolume($unit = null);


    /**
     * Get item's value
     * @return mixed
     */
    public function getValue();
}