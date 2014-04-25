<?php

namespace PHPMockito\Run;


use PHPMockito\Action\ExpectedMethodCall;
use PHPMockito\Action\FullyActionedMethodCall;
use PHPMockito\Action\CallableMethod;
use PHPMockito\Expectancy\ExpectancyEngine;
use PHPMockito\Expectancy\InitialisationCallRegistrar;
use PHPMockito\Expectancy\InitialisationCallRegistrarImpl;

class RuntimeState {
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
        $dependencyFactory = new DependencyFactory();
        $this->expectancyEngine = $dependencyFactory->createExpectancyEngine();

        $dependencyFactory->setInitialisationCallRegistrar( $this->getInitialisationCallRegistrarInstance() );

        $this->dependencyFactory = $dependencyFactory;

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
     * @param string $fullyQualifiedClassName
     */
    public function addIgnorableNonProductionTestClass( $fullyQualifiedClassName ) {
        $this->dependencyFactory->addIgnorableNonProductionTestClass( $fullyQualifiedClassName );
    }


    /**
     * @return \PHPMockito\Action\MethodCallActionInitialiser
     */
    public function createMethodCallActionInitialiser() {
        $initialisationCallRegistrar = $this->getInitialisationCallRegistrarInstance();
        return $this->dependencyFactory->createMethodCallActionInitialiser(
                $initialisationCallRegistrar,
                $initialisationCallRegistrar->getLastInitialisationMethodCall()
        );
    }


    private function getInitialisationCallRegistrarInstance() {
        static $initialisationCallRegistrar;
        if ( $initialisationCallRegistrar == null ) {
            $initialisationCallRegistrar = new InitialisationCallRegistrarImpl( $this->expectancyEngine );
        }

        return $initialisationCallRegistrar;

    }

}
 