<?php

namespace PHPMockito\Run;


use PHPMockito\Action\DebugBackTraceMethodCall;
use PHPMockito\Action\FullyActionedMethodCall;
use PHPMockito\Caster\ValueCasterFactory;
use PHPMockito\Expectancy\ExpectancyEngine;
use PHPMockito\Expectancy\InitialisationCallRegistrar;

class RuntimeState implements InitialisationCallRegistrar {
    const CLASS_NAME = __CLASS__;

    /** @var RuntimeState */
    private static $instance;

    private $expectancyEngine;

    /** @var \PHPMockito\Run\DependencyFactory */
    private $dependencyFactory;


    function __construct() {
        $this->dependencyFactory = new DependencyFactory( $this );
        $this->expectancyEngine = $this->dependencyFactory->createExpectancyEngine();
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
     * @return \PHPMockito\Run\DependencyFactory
     */
    public function getDependencyFactory() {
        return $this->dependencyFactory;
    }


    public function registerMockMethodExpectancy( FullyActionedMethodCall $fullyActionedMethodCall ) {
        $this->expectancyEngine->registerMockMethodExpectancy( $fullyActionedMethodCall );
    }


    public function retrieveMockMethodAction( DebugBackTraceMethodCall $methodCall ) {
        return $this->expectancyEngine->retrieveMockMethodAction( $methodCall );
    }
}
 