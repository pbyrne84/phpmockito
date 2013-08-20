<?php

namespace PHPMockito\Run;


use PHPMockito\Action\MethodCallListener;
use PHPMockito\Action\MethodCallListenerFactory;
use PHPMockito\CallMatching\CallMatcher;
use PHPMockito\Caster\ValueCasterFactory;
use PHPMockito\Expectancy\ExpectancyEngine;
use PHPMockito\Expectancy\InitialisationCallListener;
use PHPMockito\Expectancy\InitialisationCallListenerFactory;
use PHPMockito\Expectancy\InitialisationCallRegistrar;
use PHPMockito\Expectancy\PhpUnitTestCaseInitialisationMatcher;
use PHPMockito\Mock\Logger\FileBasedMockedClassCodeLogger;
use PHPMockito\Mock\MockClassCodeGenerator;
use PHPMockito\Mock\MockedMethodListFactory;
use PHPMockito\Mock\MockFactory;

class DependencyFactory implements InitialisationCallListenerFactory, MethodCallListenerFactory {
    const CLASS_NAME = __CLASS__;

    /** @var \PHPMockito\Mock\MockFactory */
    private $mockFactory;

    /** @var \PHPMockito\Expectancy\InitialisationCallRegistrar */
    private $initialisationCallRegistrar;


    function __construct( InitialisationCallRegistrar $initialisationCallRegistrar ) {
        $this->mockFactory = new MockFactory(
            new MockClassCodeGenerator(),
            $this,
            new MockedMethodListFactory(),
            new FileBasedMockedClassCodeLogger()
        );

        $this->initialisationCallRegistrar = $initialisationCallRegistrar;
    }


    /**
     * @return MockFactory
     */
    public function getMockFactory() {
        return $this->mockFactory;
    }


    /**
     * @return InitialisationCallListener
     */
    public function createInitialisationCallListener() {
        return new InitialisationCallListener(
            array( new PhpUnitTestCaseInitialisationMatcher() )
        );
    }


    /**
     * @return MethodCallListener
     */
    public function createMethodCallListener() {
        return new MethodCallListener( $this, $this->initialisationCallRegistrar );
    }


    /**
     * @return ExpectancyEngine
     */
    public function createExpectancyEngine() {
        return new ExpectancyEngine(
            new CallMatcher( new ValueCasterFactory() )
        );
    }

}
 