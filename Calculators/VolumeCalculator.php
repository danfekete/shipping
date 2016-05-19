<?php

namespace Webshop\Components\Shipping\Calculators;


use Webshop\Components\Configurable\ConfigOptionsTrait;
use Webshop\Components\Shipping\ShipmentInterface;

class VolumeCalculator extends Calculator {
    use ConfigOptionsTrait;
    public static $name = 'volume';

    public $config_options = [
        'rate' => [
            'name' => 'Térfogatár (HUF)'
        ],
        'division' => [
            'name' => 'Osztás'
        ],
        'unit' => [
            'name' => 'Mértékegység',
            'type' => 'select',
            'values' => [
                'm3' => 'Köbméter',
                'l' => 'Liter',
                'cm3' => 'Köbcentiméter'
            ]
        ]
    ];

    function getDefaults()
    {
        return [
            'rate' => 0,
            'division' => 1
        ];
    }

    function getRequired()
    {
        return ['rate', 'division'];
    }

    public function calculate(ShipmentInterface $shipment, array $config)
    {
        return $config['rate'] * ($shipment->getTotalVolume() / $config['division']);
    }
}