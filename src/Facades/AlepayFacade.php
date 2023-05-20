<?php

namespace Nhanhchaukp\Alepay\Facades;

use Illuminate\Support\Facades\Facade;
use Nhanhchaukp\Alepay\Alepay;

final class AlepayFacade extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return Alepay::class;
    }
}
