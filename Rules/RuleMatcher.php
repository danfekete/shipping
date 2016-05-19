<?php
/**
 Copyright (c) 2015, VOOV LLC.
 All rights reserved.
 Written by Daniel Fekete
*/

namespace Webshop\Components\Shipping\Rules;


use Webshop\Components\Shipping\ShipmentInterface;
use Illuminate\Support\Collection;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class RuleMatcher {

    /**
     * @var ExpressionLanguage
     */
    private $lang;

    const MATCH_ALL = 1;
    const MATCH_ANY = 2;

    public function __construct()
    {
        $this->lang = new ExpressionLanguage();
    }

    /**
     * Match for a Collection of Rules
     * @param array|Collection $rules
     * @param ShipmentInterface $shipment
     * @param int $matchType MATCH_ALL if you need all the rules to match, otherwise only one is needed
     */
    public function match(Collection $rules, ShipmentInterface $shipment, $matchType = RuleMatcher::MATCH_ALL)
    {
        $excluded = [];
        foreach ($rules as $rule) {
            /** @var RuleInterface $rule */
            $ret = $this->lang->evaluate($rule->getExpression(), ['shipment' => $shipment]);
            if(!is_bool($ret)) continue; // lehet inkább throw exception

        }

    }
}