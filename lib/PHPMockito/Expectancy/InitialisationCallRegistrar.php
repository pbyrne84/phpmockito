<?php

namespace PHPMockito\Expectancy;

use PHPMockito\Action\ExpectedMethodCall;
use PHPMockito\Action\FullyActionedMethodCall;
use PHPMockito\Action\CallableMethod;

interface InitialisationCallRegistrar {
    const INTERFACE_InitalisationCallRegistrar = __CLASS__;


    /**
     * @param FullyActionedMethodCall $fullyActionedMethodCall
     */
    public function registerMockMethodExpectancy( FullyActionedMethodCall $fullyActionedMethodCall );


    /**
     * @param CallableMethod $actualProductionMethodCall
     *
     * @return mixed|null
     */
    public function retrieveMockMethodAction( CallableMethod $actualProductionMethodCall );


    /**
     * @param ExpectedMethodCall $methodCall
     */
    public function registerLatestInitialisationSignature( ExpectedMethodCall $methodCall = null );


    /**
     * @return CallableMethod
     */
    public function getLastInitialisationMethodCall();


    /**
     * @param CallableMethod $actualProductionMethodCall
     *
     * @return bool
     */
    public function hasMockMethodAction( CallableMethod $actualProductionMethodCall );
}
