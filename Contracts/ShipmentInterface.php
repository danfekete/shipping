<?php
/**
 * Created by PhpStorm.
 * User: dande
 * Date: 22/09/15
 * Time: 00:06
 */

namespace Webshop\Components\Shipping\Contracts;


use Webshop\Components\Address\Contracts\AddressInterface;
use Illuminate\Support\Collection;

interface ShipmentInterface {

    /**
     * Set the destination address
     * @param AddressInterface $address
     * @return mixed
     */
    public function setDestinationAddress(AddressInterface $address);

    /**
     * Return the destination address
     * @return AddressInterface
     */
    public function getDestinationAddress();

    /**
     * Get the unit for the mass
     * @return string
     */
    public function getMassUnit();


    /**
     * Set the unit for the mass
     * @param string $massUnit
     */
    public function setMassUnit($massUnit);

    /**
     * Get the unit for the length
     * @return string
     */
    public function getLengthUnit();

    /**
     * Set unit for the length
     * @param string $lengthUnit
     */
    public function setLengthUnit($lengthUnit);

    /**
     * Add a shippable to the shipment
     * @param ShippableInterface $shippableInterface
     * @return mixed
     */
    public function addShippable(ShippableInterface $shippableInterface);

    /**
     * Calculate the total weight of the shipment
     * @return mixed
     */
    public function getTotalWeight();

    /**
     * Calculate the total volume of the package
     * @return mixed
     */
    public function getTotalVolume();


    /**
     * Get the total value of the shipment
     * @return mixed
     */
    public function getTotalValue();

    /**
     * Return the number of items
     * @return mixed
     */
    public function getItemCount();

    /**
     * Return the collection of the items
     * @return Collection|ShippableInterface[]
     */
    public function getShipment();

    /**
     * Return TRUE if shipment is Cash-on-delivery
     * @return boolean
     */
    public function isCOD();

    /**
     * Set COD
     * @param boolean $value
     */
    public function setCOD($value);
}