<?php
/**
 * Copyright (c) 2016, VOOV LLC.
 * All rights reserved.
 * Written by Daniel Fekete
 * daniel.fekete@voov.hu
 */

namespace Webshop\Components\Shipping\Services;


use Carbon\Carbon;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Webshop\Components\Address\Contracts\AddressInterface;
use Webshop\Components\Shipping\Contracts\LabelInterface;
use Webshop\Components\Shipping\Contracts\ParcelInterface;
use Webshop\Components\Shipping\Contracts\ParcelServiceInterface;
use Webshop\Components\Shipping\Contracts\ParcelStatusInterface;
use Webshop\Components\Shipping\Contracts\ShipmentInterface;
use Webshop\Components\Shipping\Exceptions\RequiredOptionsMissing;
use Webshop\Components\Shipping\Exceptions\ResponseError;
use Webshop\Components\Shipping\Exceptions\SourceAddressMissing;
use Webshop\Components\Shipping\Exceptions\TrackingCodeNotFound;

class GLS implements ParcelServiceInterface
{

    protected $urls = [
        'HU' => 'http://online.gls-hungary.com/webservices/soap_server.php?wsdl&ver=14.05.20.01',
        'SK' => 'http://online.gls-slovakia.sk/webservices/soap_server.php?wsdl&ver=14.05.20.01',
        'CZ' => 'http://online.gls-czech.com/webservices/soap_server.php?wsdl&ver=14.05.20.01',
        'RO' => 'http://online.gls-romania.ro/webservices/soap_server.php?wsdl&ver=14.05.20.01',
        'SI' => 'http://connect.gls-slovenia.com/webservices/soap_server.php?wsdl&ver=14.05.20.01',
        'HR' => 'http://online.gls-croatia.com/webservices/soap_server.php?wsdl&ver=14.05.20.01',
    ];

    protected $url;
    protected $options;
    /**
     * @var AddressInterface
     */
    protected $sourceAddress;
    /**
     * @var array
     */
    protected $services = [];

    /**
     * @var LabelInterface
     */
    protected $labelType;

    /**
     * GLS constructor.
     */
    public function __construct()
    {

    }

    public function addService($serviceCode, $param)
    {
        $this->services[] = [
            'code' => $serviceCode,
            'info' => $param
        ];
    }

    /**
     * Calculate GLS hash
     * @param $data
     * @return string
     */
    private function getHash($data) {
        $d = collect($data);
        $hashBase = implode('', $d->except(['services', 'hash', 'timestamp', 'printit', 'printertemplate'])->all());
        return sha1($hashBase);
    }


    /**
     * Return the parcel service provider name
     * @return string
     */
    public function getProviderName()
    {
        return "GLS";
    }

    public function setSourceAddress(AddressInterface $address)
    {
        $this->sourceAddress = $address;
    }

    /**
     * Generate parcel data from a shipment interface
     * @param ShipmentInterface $shipment
     * @return ParcelInterface
     */
    public function generateParcel(ShipmentInterface $shipment)
    {
        if(empty($this->sourceAddress)) throw new SourceAddressMissing;
        if(empty($this->options)) throw new RequiredOptionsMissing;

        $url = $this->urls[$this->options['countryCode']];
        $client = new \SoapClient($url);
        $args = [
            'username' => $this->options['username'],
            'password' => $this->options['password'],
            'senderid' => $this->options['senderid'],
            // From:
            'sender_name' => $this->sourceAddress->getName(),
            'sender_address' => $this->sourceAddress->getFullStreetAddress(),
            'sender_city' => $this->sourceAddress->getCity(),
            'sender_zipcode' => $this->sourceAddress->getPostcode(),
            'sender_country' => $this->sourceAddress->getCountryCode(),
            'sender_phone' => '',
            'sender_email' => '',
            // To:
            'consig_name' => $shipment->getDestinationAddress()->getName(),
            'consig_address' => $shipment->getDestinationAddress()->getFullStreetAddress(),
            'consig_city' => $shipment->getDestinationAddress()->getCity(),
            'consig_zipcode' => $shipment->getDestinationAddress()->getPostcode(),
            'consig_country' => $shipment->getDestinationAddress()->getCountryCode(),
            'consig_phone' => '',
            'consig_email' => '',
            // Package
            'content' => $this->options['content'],
            'clientref' => '',
            'codamount' => $shipment->isCOD() ? $shipment->getTotalValue() : 0,
            'codref' => $this->options['CODRef'],
            'services' => $this->services,
            'printertemplate' => $this->labelType->getType(),
            'printit' => $this->labelType->getType() == LabelInterface::NONE ? false : true,
            'timestamp' => Carbon::now()->format('YmdHis'),
            'pcount' => $this->options['packageCount'],
            'pickupdate' => $this->options['pickupDate']
        ];
        $args['hash'] = $this->getHash($args);
        $response = $client->__soapCall('printlabel', $args);
        if($response['successfull'] == false ) throw new ResponseError($this, $response['errdesc'], $response['errcode']);

        return new Parcel($response['pcls'][0], base64_decode($response['pdfdata']));
    }



    /**
     * Return a tracking URL for a given parcel
     * @param ParcelInterface $parcel
     * @return mixed
     */
    public function getTrackingURL(ParcelInterface $parcel)
    {
        $language = \App::getLocale();
        return "http://online.gls-hungary.com/tt_page.php?tt_value={$parcel->getParcelId()}&lng=$language";
    }

    /**
     * Return the tracking page
     * @param ParcelInterface $parcel
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function getTrackingPage(ParcelInterface $parcel)
    {
        $client = new Client();
        return $client->get($this->getTrackingURL($parcel));
    }

    /**
     * Return the parcel status data
     * @param ParcelInterface $parcel
     * @return ParcelStatusInterface|ParcelStatusInterface[]
     */
    public function getParcelStatus(ParcelInterface $parcel)
    {
        
        $html = $this->getTrackingPage($parcel)->getBody()->getContents();

        $dom = new Crawler($html);
        $rows = $dom->filter('table tr.colored_0, table tr.colored_1')->each(function(Crawler $node) {
            return new GLSStatus($node);
        });

        if (!count($rows)) throw new TrackingCodeNotFound($parcel->getParcelId());

        return $rows;
    }

    /**
     * Calculate rate for Shipment
     * @param ShipmentInterface $shipment
     * @return mixed
     */
    public function calculateRate(ShipmentInterface $shipment)
    {
        // TODO: Implement calculateRate() method.
    }

    /**
     * Set the label type to print
     * @param LabelInterface $label
     * @return mixed
     */
    public function setLabelType(LabelInterface $label)
    {
        $this->labelType = $label;
    }


    /**
     * Set the service options
     * @param $options
     */
    protected function setOptions($options)
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'countryCode' => 'HU',
            //'content' => 'Package',
            'packageCount' => 1,
            'pickupDate' => Carbon::now(),
            'username' => env('GLS_USERNAME', ''),
            'password' => env('GLS_PASSWORD', ''),
            'senderid' => env('GLS_SENDER_ID', ''),
            //'COD' => 0,
            'CODRef' => '', // only needed when COD is > 0,
            'printerTemplate' => 'A6_ONA4',
            'printLabel' => true,
        ]);
        $resolver->setRequired(['countryCode', 'packageCount', 'pickupDate', 'username', 'password', 'senderid', 'printerTemplate', 'printLabel']);
        $resolver->setAllowedTypes('pickupDate', Carbon::class);
        $resolver->setAllowedTypes('COD', 'double');
        $resolver->setAllowedTypes('printLabel', 'bool');
        $this->options = $resolver->resolve($options);
    }

    /**
     * Return the service options
     * @return array
     */
    protected function getOptions()
    {
        return $this->options;
    }

    /**
     * Return TRUE if service can create a parcel
     * @return boolean
     */
    public function canCreateParcel()
    {
        return true;
    }

    /**
     * Return TRUE if it can print a label for the parcel
     * @return boolean
     */
    public function canPrintLabel()
    {
        return true;
    }

    /**
     * Return TRUE if it can calculate rate for parcel
     * @return boolean
     */
    public function canCalculateRate()
    {
        return false;
    }

    /**
     * Return TRUE if it can track the parcel
     * @return boolean
     */
    public function canTrackParcel()
    {
        return true;
    }


}