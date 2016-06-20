<?php
/**
 * Copyright (c) 2016, VOOV LLC.
 * All rights reserved.
 * Written by Daniel Fekete
 * daniel.fekete@voov.hu
 */

namespace Webshop\Components\Shipping\Contracts;


use Carbon\Carbon;

interface ParcelStatusInterface
{

    const PARCEL_PICKUP = 0; // Service registered the request for the parcel, and ready to pickup
    const PARCEL_ENROUTE = 1; // Parcel is en route to customer
    const PARCEL_DELIVERED = 2; // Parcel is delivered to customer
    const PARCEL_EXCEPTION = 3; // Customs hold or return shipment
    const PARCEL_FAILURE = 4; // Failure occurred
    const PARCEL_NOINFO = 5; // No info on shipment for some other reason

    /**
     * Return the raw status string as recorded
     * @return string
     */
    public function getRawStatus();

    /**
     * Get parsed status info
     * @return int
     */
    public function getStatus();

    /**
     * Get the city name where the status was recorded
     * @return string
     */
    public function getCity();

    /**
     * Return the timestamp for the status
     * @return Carbon
     */
    public function getDateTime();


    /**
     * Any other data returned as an array
     * @return array
     */
    public function getExtraInfo();
}