<?php

namespace PHPMockito\Expectancy;


use PHPMockito\Action\DebugBackTraceMethodCall;
use PHPMockito\Action\FullyActionedMethodCall;

interface InitialisationCallRegistrar {
    const INTERFACE_InitalisationCallRegistrar = __CLASS__;


    public function registerMockMethodExpectancy( FullyActionedMethodCall $fullyActionedMethodCall );


    public function retrieveMockMethodAction( DebugBackTraceMethodCall $methodCall );
}
