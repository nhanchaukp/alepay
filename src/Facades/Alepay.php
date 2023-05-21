<?php

namespace Nhanchaukp\Alepay\Facades;

final class Alepay extends \Illuminate\Support\Facades\Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return \Nhanchaukp\Alepay\Alepay::class;
    }
}
