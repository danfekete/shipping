<?php
/**
 * Copyright (c) 2015, VOOV LLC.
 * All rights reserved.
 * Written by Daniel Fekete
 */

namespace Webshop\Components\Shipping;


use Webshop\Components\Configurable\ConfigurableTrait;
use Webshop\Components\Shipping\Address\Models\Zones;
use Webshop\Components\Shipping\Calculators\CalculatorInterface;
use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
	use ConfigurableTrait;

	protected $guarded = ['id', 'users_id', 'uploader_id'];

	protected static $rules = [
		'name' => 'required',
		'calculator' => 'required',
	];

	/**
	 * @return CalculatorInterface
	 */
	public function getCalculator()
	{
		return app('shipping.calculators.' . $this->calculator);
	}

	public function calculate(ShipmentInterface $shipment)
	{
		$calc = $this->getCalculator();
		return $calc->calculate($shipment, $this->config->data);
	}

	/**
	 * Get shipping method zone
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function zone() {
		return $this->belongsTo(Zones::class);
	}
}