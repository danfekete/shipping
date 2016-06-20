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
}