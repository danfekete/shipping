<?php

namespace Webshop\Components\Shipping\Contracts;

use Illuminate\Contracts\Foundation\Application;

interface CalculatorInterface {
    public static function register(Application $app);
    public function calculate(ShipmentInterface $shipment, array $config);
}