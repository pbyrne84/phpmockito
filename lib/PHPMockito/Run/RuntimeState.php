<?php

namespace PHPMockito\Run;


use PHPMockito\Action\ExpectedMethodCall;
use PHPMockito\Action\FullyActionedMethodCall;
use PHPMockito\Action\MethodCall;
use PHPMockito\Expectancy\InitialisationCallRegistrar;

class RuntimeState implements InitialisationCallRegistrar {
    const CLASS_NAME = __CLASS__;

    /** @var RuntimeState */
    private static $instance;

    private $expectancyEngine;

    /** @var \PHPMockito\Run\DependencyFactory */
    private $dependencyFactory;

    private $overrideIsTestCaseCheck = false;


    private function __construct() {
        $this->dependencyFactory = new DependencyFactory( $this );
        $this->expectancyEngine  = $this->dependencyFactory->createExpectancyEngine();
    }


    /**
     * @return RuntimeState
     */
    public static function getInstance() {
        if ( !isset( self::$instance ) ) {
            self::$instance = new RuntimeState();
        }

        return self::$instance;
    }


    /**
     * @return boolean
     */
    public function getOverrideIsTestCaseCheck() {
        return $this->overrideIsTestCaseCheck;
    }


    /**
     * @param boolean $overrideIsTestCaseCheck
     *
     * @return $this
     */
    public function setOverrideIsTestCaseCheck( $overrideIsTestCaseCheck ) {
        $this->overrideIsTestCaseCheck = $overrideIsTestCaseCheck;

        return $this;
    }


    public function isStrictMode() {
        return false;
    }


    /**
     * @return \PHPMockito\Run\DependencyFactory
     */
    public function getDependencyFactory() {
        return $this->dependencyFactory;
    }


    /**
     * @param FullyActionedMethodCall $fullyActionedMethodCall
     */
    public function registerMockMethodExpectancy( FullyActionedMethodCall $fullyActionedMethodCall ) {
        $this->expectancyEngine->registerMockMethodExpectancy( $fullyActionedMethodCall );
    }


    /**
     * @param MethodCall $actualProductionMethodCall
     *
     * @return mixed|null
     */
    public function retrieveMockMethodAction( MethodCall $actualProductionMethodCall ) {
        return $this->expectancyEngine->retrieveMockMethodAction( $actualProductionMethodCall );
    }


    /**
     * @param ExpectedMethodCall $methodCall
     */
    public function registerLatestInitialisationSignature( ExpectedMethodCall $methodCall = null ) {
        $this->expectancyEngine->registerLatestInitialisationSignature( $methodCall );
    }


    /**
     * @return ExpectedMethodCall
     */
    public function getLastInitialisationMethodCall() {
        return $this->expectancyEngine->getLastInitialisationMethodCall();
    }


    public function hasMockMethodAction( MethodCall $actualProductionMethodCall ) {
       return $this->expectancyEngine->hasMockMethodAction( $actualProductionMethodCall );
    }


    public function newMethodCallActionInitialiser() {
        return $this->dependencyFactory->newMethodCallActionInitialiser(
            $this,
            $this->getLastInitialisationMethodCall()
        );
    }
}
 