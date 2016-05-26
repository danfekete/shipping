<?php

namespace Webshop\Components\Shipping\Calculators;


use Webshop\Components\Shipping\Contracts\ShipmentInterface;

class FlatRateCalculator extends Calculator {

    use ConfigOptionsTrait;

    public $config_options = [
        'rate' => [
            'name' => 'Alapár (HUF)'
        ]
    ];

    public static $name = 'flat_rate';

    function getDefaults()
    {
        return [];
    }

    function getRequired()
    {
        return [];
    }

    public function calculate(ShipmentInterface $shipment, array $config)
    {
        return $config['rate'];
    }
}