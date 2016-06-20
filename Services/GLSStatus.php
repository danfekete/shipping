<?php
/**
 * Copyright (c) 2016, VOOV LLC.
 * All rights reserved.
 * Written by Daniel Fekete
 * daniel.fekete@voov.hu
 */

namespace Webshop\Components\Shipping\Services;


use Carbon\Carbon;
use Symfony\Component\DomCrawler\Crawler;
use Webshop\Components\Shipping\Contracts\ParcelStatusInterface;

class GLSStatus implements ParcelStatusInterface
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * GLSStatus constructor.
     * @param Crawler $crawler
     */
    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * Return the raw status string as recorded
     * @return string
     */
    public function getRawStatus()
    {
        return trim($this->crawler->filter('td')->eq(0)->text());
    }

    /**
     * Get parsed status info
     * @return int
     */
    public function getStatus()
    {
        $status = $this->getRawStatus();
        $matches = [];
        preg_match('/([0-9]+?) ?- ?.+/', $status, $matches);
        switch($matches[1]) {
            case '05':
                return ParcelStatusInterface::PARCEL_DELIVERED;
            case '51':
            case '52':
                return ParcelStatusInterface::PARCEL_PICKUP;
            case '03':
            case '04':
                return ParcelStatusInterface::PARCEL_ENROUTE;
            default:
                return ParcelStatusInterface::PARCEL_NOINFO;
        }
    }

    /**
     * Get the city name where the status was recorded
     * @return string
     */
    public function getCity()
    {
        return $this->crawler->filter('td')->eq(2)->text();
    }

    /**
     * Return the timestamp for the status
     * @return Carbon
     */
    public function getDateTime()
    {
        return Carbon::parse($this->crawler->filter('td')->eq(0)->text());
    }

    /**
     * Any other data returned as an array
     * @return array
     */
    public function getExtraInfo()
    {
        return [
            'info' => $this->crawler->filter('td')->eq(3)->text()
        ];
    }


}