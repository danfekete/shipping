<?php

namespace Webshop\Components\Shipping;


use Webshop\Components\Address\Contracts\AddressInterface;
use Illuminate\Support\Collection;
use Webshop\Components\Shipping\Contracts\ShipmentInterface;
use Webshop\Components\Shipping\Contracts\ShippableInterface;

class Shipment implements ShipmentInterface {


    /**
     * @var AddressInterface
     */
    protected $address;
    protected $cod;

    private $shipment;

    protected $massUnit = 'g';

    protected $lengthUnit = 'mm';

    /**
     * Get the unit for the mass
     * @return string
     */
    public function getMassUnit()
    {
        return $this->massUnit;
    }

    /**
     * Set the unit for the mass
     * @param string $massUnit
     */
    public function setMassUnit($massUnit)
    {
        $this->massUnit = $massUnit;
    }

    /**
     * Get the unit for the length
     * @return string
     */
    public function getLengthUnit()
    {
        return $this->lengthUnit;
    }

    /**
     * Set unit for the length
     * @param string $lengthUnit
     */
    public function setLengthUnit($lengthUnit)
    {
        $this->lengthUnit = $lengthUnit;
    }


    function __construct()
    {
        $this->shipment = new Collection();
    }


    /**
     * Add a shippable to the shipment
     * @param ShippableInterface $shippableInterface
     * @return mixed
     */
    public function addShippable(ShippableInterface $shippableInterface)
    {
        $this->shipment[] = $shippableInterface;
    }

    /**
     * Calculate the total weight of the shipment
     * @return mixed
     */
    public function getTotalWeight()
    {
        return $this->shipment->sum(function($item) {
            /** @var ShippableInterface $item */
            return $item->getWeight($this->getMassUnit());
        });

    }

    /**
     * Calculate the total volume of the package
     * @return mixed
     */
    public function getTotalVolume()
    {
        return $this->shipment->sum(function($item) {
            /** @var ShippableInterface $item */
            return $item->getVolume($this->getLengthUnit());
        });
    }

    /**
     * Get the total value of the shipment
     * @return mixed
     */
    public function getTotalValue()
    {
        return $this->shipment->sum(function($item) {
            /** @var ShippableInterface $item */
            return $item->getValue();
        });
    }

    /**
     * Return the collection of the items
     * @return Collection|ShippableInterface[]
     */
    public function getShipment()
    {
        return $this->shipment;
    }

    /**
     * Return the number of items
     * @return mixed
     */
    public function getItemCount()
    {
        return count($this->shipment);
    }

    /**
     * Set the destination address
     * @param AddressInterface $address
     * @return mixed
     */
    public function setDestinationAddress(AddressInterface $address)
    {
        $this->address = $address;
    }

    /**
     * Return the destination address
     * @return AddressInterface
     */
    public function getDestinationAddress()
    {
        return $this->address;
    }

    /**
     * Return TRUE if shipment is Cash-on-delivery
     * @return boolean
     */
    public function isCOD()
    {
        $this->cod = true;
    }

    /**
     * Set COD
     * @param boolean $value
     */
    public function setCOD($value) {
        $this->cod = $value;
    }
}