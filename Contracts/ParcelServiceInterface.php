<?php
/**
 * Copyright (c) 2016, VOOV LLC.
 * All rights reserved.
 * Written by Daniel Fekete
 * daniel.fekete@voov.hu
 */

namespace Webshop\Components\Shipping\Contracts;

interface ParcelServiceInterface
{
    /**
     * Return TRUE if service can create a parcel
     * @return boolean
     */
    public function canCreateParcel();

    /**
     * Return TRUE if it can print a label for the parcel
     * @return boolean
     */
    public function canPrintLabel();

    /**
     * Return TRUE if it can calculate rate for parcel
     * @return boolean
     */
    public function canCalculateRate();

    /**
     * Return TRUE if it can track the parcel
     * @return boolean
     */
    public function canTrackParcel();

    /**
     * Return the parcel service provider name
     * @return string
     */
    public function getProviderName();

    /**
     * Generate parcel data from a shipment interface
     * @param ShipmentInterface $shipment
     * @return ParcelInterface
     */
    public function generateParcel(ShipmentInterface $shipment);

    /**
     * Calculate rate for Shipment
     * @param ShipmentInterface $shipment
     * @return mixed
     */
    public function calculateRate(ShipmentInterface $shipment);

    /**
     * Return a tracking URL for a given parcel
     * @param ParcelInterface $parcel
     * @return mixed
     */
    public function getTrackingURL(ParcelInterface $parcel);


    /**
     * Return the parcel status data
     * @param ParcelInterface $parcel
     * @return ParcelStatusInterface
     */
    public function getParcelStatus(ParcelInterface $parcel);

    /**
     * Set the label type to print
     * @param LabelInterface $label
     * @return mixed
     */
    public function setLabelType(LabelInterface $label);
}