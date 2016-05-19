<?php

namespace Webshop\Components\Shipping\Calculators;


use Webshop\Components\Configurable\ConfigOptionsTrait;
use Webshop\Components\Shipping\ShipmentInterface;

class WeightRateCalculator extends Calculator {
    use ConfigOptionsTrait;
    public static $name = 'weight_rate';

    public $config_options = [
        'rate' => [
            'name' => 'Alapár (HUF)'
        ],
        'division' => [
            'name' => 'Osztás'
        ],
        'multiplier' => [
            'name' => 'Egységár (HUF)'
        ]
    ];

    function getDefaults()
    {
        return [
            'base' => 0,
            'division' => 1,
            'multiplier' => 1
        ];
    }

    function getRequired()
    {
        return [
            'multiplier'
        ];
    }

    public function calculate(ShipmentInterface $shipment, array $config)
    {
        //$this->resolver->resolve($config);
        return $config['base'] + ($config['multiplier'] * round($shipment->getTotalWeight() / $config['division']));
    }
}