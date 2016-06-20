<?php
/**
 * Copyright (c) 2016, VOOV LLC.
 * All rights reserved.
 * Written by Daniel Fekete
 * daniel.fekete@voov.hu
 */

namespace Webshop\Components\Shipping\Services;


use Webshop\Components\Shipping\Contracts\ParcelInterface;
use Webshop\Components\Shipping\Exceptions\NoPrintableFound;

class Parcel implements ParcelInterface
{

    private $parcelId;

    private $pdfData = null;

    /**
     * Parcel constructor.
     * @param $parcelId
     * @param null|string $pdfData
     */
    public function __construct($parcelId, $pdfData=null)
    {
        $this->parcelId = $parcelId;
        $this->pdfData = $pdfData;
    }


    /**
     * Return the Parcel's tracking ID
     * @return mixed
     */
    public function getParcelId()
    {
        return $this->parcelId;
    }

    /**
     * Return TRUE if Parcel has printable document
     * @return boolean
     */
    public function hasPrintable()
    {
        return empty($this->pdfData);
    }

    /**
     * If parcel has printable document, save the document to path
     * @param $path
     * @return \SplFileInfo
     */
    public function savePDF($path)
    {
        file_put_contents($path, $this->getRawPDF());
        return new \SplFileInfo($path);
    }

    /**
     * Get the Raw PDF document for the parcel
     * @return string
     * @throws NoPrintableFound
     */
    public function getRawPDF()
    {
        if(!$this->hasPrintable()) throw new NoPrintableFound();
        return $this->pdfData;
    }

    /**
     * Show the printable document in the browser
     * @return mixed
     */
    public function showPDF()
    {
        //if(!headers_sent()) return
        $pdfData = $this->getRawPDF();

        header('Content-type: application/pdf');
        header('Content-Disposition: inline; filename="parcel.pdf"');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . strlen($pdfData));
        header('Accept-Ranges: bytes');

        echo $pdfData;
    }
}