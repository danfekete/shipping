<?php
/**
 * Copyright (c) 2016, VOOV LLC.
 * All rights reserved.
 * Written by Daniel Fekete
 * daniel.fekete@voov.hu
 */

namespace Webshop\Components\Shipping\Exceptions;


use Webshop\Components\Shipping\Contracts\ParcelServiceInterface;

class ResponseError extends \Exception
{

    /**
     * ResponseError constructor.
     */
    public function __construct(ParcelServiceInterface $service, $code, $description='')
    {
        parent::__construct(sprintf("%s service returned: %s (%s)", $service->getProviderName(), $description, $code));
    }
}