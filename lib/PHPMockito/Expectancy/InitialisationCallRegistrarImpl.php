<?php

namespace PHPMockito\Expectancy;


use PHPMockito\Action\CallableMethod;
use PHPMockito\Action\ExpectedMethodCall;
use PHPMockito\Action\FullyActionedMethodCall;

class InitialisationCallRegistrarImpl implements InitialisationCallRegistrar {

    const CLASS_NAME = __CLASS__;
    /** @var ExpectancyEngine */
    private $expectancyEngine;


    function __construct( ExpectancyEngine $expectancyEngine ) {
        $this->expectancyEngine = $expectancyEngine;
    }


    /**
     * @param FullyActionedMethodCall $fullyActionedMethodCall
     */
    public function registerMockMethodExpectancy( FullyActionedMethodCall $fullyActionedMethodCall ) {
        $this->expectancyEngine->registerMockMethodExpectancy( $fullyActionedMethodCall );
    }


    /**
     * @param CallableMethod $actualProductionMethodCall
     *
     * @return mixed|null
     */
    public function retrieveMockMethodAction( CallableMethod $actualProductionMethodCall ) {
        return $this->expectancyEngine->retrieveMockMethodAction( $actualProductionMethodCall );
    }


    /**
     * @param ExpectedMethodCall $methodCall
     */
    public function registerLatestInitialisationSignature( ExpectedMethodCall $methodCall = null ) {
        $this->expectancyEngine->registerLatestInitialisationSignature( $methodCall );
    }


    /**
     * @inheritdoc
     */
    public function getLastInitialisationMethodCall() {
        return $this->expectancyEngine->getLastInitialisationMethodCall();
    }


    /**
     * @param CallableMethod $actualProductionMethodCall
     *
     * @return bool
     */
    public function hasMockMethodAction( CallableMethod $actualProductionMethodCall ) {
        return $this->expectancyEngine->hasMockMethodAction( $actualProductionMethodCall );
    }
}