<?php
/**
 * Copyright (c) 2015, VOOV LLC.
 * All rights reserved.
 * Written by Daniel Fekete
 */

namespace Webshop\Components\Shipping\Contracts;


interface ConfigLoaderInterface
{
	/**
	 * Return the config
	 * @return array
	 */
	public function getConfig();
}