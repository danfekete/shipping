<?php
/**
 * Copyright (c) 2016, VOOV LLC.
 * All rights reserved.
 * Written by Daniel Fekete
 * daniel.fekete@voov.hu
 */

namespace Webshop\Components\Shipping\Contracts;


interface ParcelInterface
{
    /**
     * Return the Parcel's tracking ID
     * @return mixed
     */
    public function getParcelId();

    /**
     * Return TRUE if Parcel has printable document
     * @return boolean
     */
    public function hasPrintable();

    /**
     * If parcel has printable document, save the document to path
     * @param $path
     * @return \SplFileInfo
     */
    public function savePDF($path);

    /**
     * Get the Raw PDF document for the parcel
     * @return string
     */
    public function getRawPDF();

    /**
     * Show the printable document in the browser
     * @return mixed
     */
    public function showPDF();
}