<?php
/**
 Copyright (c) 2015, VOOV LLC.
 All rights reserved.
 Written by Daniel Fekete
*/

namespace Webshop\Components\Shipping\Rules;


interface RuleInterface {
    public function getExpression();
}