<?php

namespace Webshop\Components\Shipping\Calculators;


use Illuminate\Contracts\Foundation\Application;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Webshop\Components\Shipping\Contracts\CalculatorInterface;

abstract class Calculator implements CalculatorInterface {

    protected $resolver;

    abstract function getDefaults();

    abstract function getRequired();

    public static $name = 'abstract';

    public static function register(Application $app)
    {
        $app->bind('shipping.calculators.' . static::$name, static::class);
    }


    function __construct()
    {

        $this->resolver = new OptionsResolver();
        $this->resolver->setDefaults(static::getDefaults());
        $this->resolver->setRequired(static::getRequired());
    }

    /**
     * Return the default unit for the mass
     * @return string
     */
    public function getDefaultMassUnit()
    {
        return 'g';
    }

    /**
     * Return the default unit for length
     * @return string
     */
    public function getDefaultLengthUnit() {
        return 'mm';
    }
}