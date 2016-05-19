<?php

namespace Webshop\Components\Shipping\Rules;

use Illuminate\Database\Eloquent\Model;

class Rule extends Model implements RuleInterface
{
    protected $table = 'shipping_rules';

    public function getExpression()
    {
        return $this->expression;
    }
}
