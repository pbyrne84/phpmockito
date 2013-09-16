<?php

namespace PHPMockito\Expectancy;

use PHPMockito\Action\FullyActionedMethodCall;
use PHPMockito\Action\MethodCall;

interface InitialisationCallRegistrar {
    const INTERFACE_InitalisationCallRegistrar = __CLASS__;


    public function registerMockMethodExpectancy( FullyActionedMethodCall $fullyActionedMethodCall );


    public function retrieveMockMethodAction( MethodCall $actualProductionMethodCall );


    public function registerLatestInitialisationSignature( MethodCall $methodCall = null );


    /**
     * @return MethodCall
     */
    public function getLastInitialisationMethodCall();


    /**
     * @param MethodCall $actualProductionMethodCall
     *
     * @return bool
     */
    public function hasMockMethodAction( MethodCall $actualProductionMethodCall );
}
