<?php

namespace PHPMockito\Run;


use PHPMockito\Action\ExpectedMethodCall;
use PHPMockito\Action\FullyActionedMethodCall;
use PHPMockito\Action\CallableMethod;
use PHPMockito\Expectancy\ExpectancyEngine;
use PHPMockito\Expectancy\InitialisationCallRegistrar;

class RuntimeState implements InitialisationCallRegistrar {
    const CLASS_NAME = __CLASS__;

    /** @var RuntimeState */
    private static $instance;

    /** @var ExpectancyEngine */
    private $expectancyEngine;

    /** @var \PHPMockito\Run\DependencyFactory */
    private $dependencyFactory;

    /** @var bool */
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


    public function hasMockMethodAction( CallableMethod $actualProductionMethodCall ) {
        return $this->expectancyEngine->hasMockMethodAction( $actualProductionMethodCall );
    }


    /**
     * @param string $fullyQualifiedClassName
     */
    public function addIgnorableNonProductionTestClass( $fullyQualifiedClassName ) {
        $this->dependencyFactory->addIgnorableNonProductionTestClass( $fullyQualifiedClassName );
    }


    /**
     * @return \PHPMockito\Action\MethodCallActionInitialiser
     */
    public function createMethodCallActionInitialiser() {
        return $this->dependencyFactory->createMethodCallActionInitialiser(
            $this,
            $this->getLastInitialisationMethodCall()
        );
    }


    /**
     * @return ExpectedMethodCall
     */
    public function getLastInitialisationMethodCall() {
        return $this->expectancyEngine->getLastInitialisationMethodCall();
    }
}
 