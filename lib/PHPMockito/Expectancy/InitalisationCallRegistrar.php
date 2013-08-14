<?php

namespace PHPMockito\Expectancy;


use PHPMockito\Action\FullyActionedMethodCall;

interface InitalisationCallRegistrar {
    const INTERFACE_InitalisationCallRegistrar = __CLASS__;


    public function registerMockMethodExpectancy( FullyActionedMethodCall $fullyActionedMethodCall );
}
