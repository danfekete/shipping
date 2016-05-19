<?php

namespace Webshop\Components\Shipping;


use PhpUnitsOfMeasure\PhysicalQuantity\Length;
use PhpUnitsOfMeasure\PhysicalQuantity\Mass;

trait ShippableTrait {
    protected $massUnit = 'g';
    protected $lengthUnit = 'mm';

    protected $weightName = 'weight';
    protected $widthName = 'width';
    protected $heightName = 'height';
    protected $depthName = 'depth';



    /**
     * Get Mass object of the given attribute
     * @param string $attributeName
     * @return Mass
     */
    public function getMass($attributeName) {
        return new Mass($this->{$attributeName}, $this->massUnit);
    }


    /**
     * Get Length object of the given attribute
     * @param $attributeName
     * @return Length
     */
    public function getLength($attributeName)
    {
        return new Length($this->{$attributeName}, $this->lengthUnit);
    }

    /**
     * Get the weight of the shippable
     * @param null $unit
     * @return float
     */
    public function getWeight($unit = null) {
        if($unit == null) $unit = $this->massUnit;
        return $this->getMass($this->weightName)->toUnit($unit);
    }


    /**
     * Get the width of the shippable
     * @param null $unit
     * @return float
     */
    public function getWidth($unit = null)
    {
        if($unit == null) $unit = $this->lengthUnit;
        return $this->getLength($this->widthName)->toUnit($unit);
    }


    /**
     * Get the height of the shippable
     * @param null $unit
     * @return float
     */
    public function getHeight($unit = null)
    {
        if($unit == null) $unit = $this->lengthUnit;
        return $this->getLength($this->heightName)->toUnit($unit);
    }

    /**
     * Get the depth of the shippable
     * @param null $unit
     * @return float
     */
    public function getDepth($unit = null)
    {
        if($unit == null) $unit = $this->lengthUnit;
        return $this->getLength($this->depthName)->toUnit($unit);
    }

    /**
     * Return the volume of the shippable
     * @param null $unit
     * @return float
     */
    public function getVolume($unit = null) {
        return $this->getWidth($unit) * $this->getHeight($unit) * $this->getDepth($unit);
    }
}