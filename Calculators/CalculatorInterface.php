<?php

namespace Webshop\Components\Shipping\Calculators;
use Webshop\Components\Shipping\ShipmentInterface;
use Illuminate\Contracts\Foundation\Application;

interface CalculatorInterface {
    public static function register(Application $app);
    public function calculate(ShipmentInterface $shipment, array $config);
}