<?php
namespace Webshop\Components\Shipping\Providers;

use Webshop\Components\Shipping\Calculators\Calculator;
use Illuminate\Support\ServiceProvider;

/**
 * Copyright (c) 2015, VOOV LLC.
 * All rights reserved.
 * Written by Daniel Fekete
 */
class ShippingProvider extends ServiceProvider
{

	private $calculators = [
		\Webshop\Components\Shipping\Calculators\FlatRateCalculator::class,
		\Webshop\Components\Shipping\Calculators\VolumeCalculator::class,
		\Webshop\Components\Shipping\Calculators\WeightRateCalculator::class,
	];

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		foreach ($this->calculators as $calc) {
			/** @var Calculator $calc */
			$calc::register($this->app); // bind the calculator to the Laravel IoC
		}
	}

	public function boot()
	{
		// when everything is all set
	}
}