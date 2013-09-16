<?php

namespace PHPMockito\Run;


use PHPMockito\Action\ExpectedMethodCall;
use PHPMockito\Action\MethodCallActionInitialiser;
use PHPMockito\Action\MethodCallListener;
use PHPMockito\Action\MethodCallListenerFactory;
use PHPMockito\CallMatching\CallMatcher;
use PHPMockito\Expectancy\ExpectancyEngine;
use PHPMockito\Expectancy\TestCaseCallVerifier;
use PHPMockito\Expectancy\InitialisationCallListenerFactory;
use PHPMockito\Expectancy\InitialisationCallRegistrar;
use PHPMockito\Expectancy\PhpUnitTestCaseInitialisationMatcher;
use PHPMockito\Mock\Logger\FileBasedMockedClassCodeLogger;
use PHPMockito\Mock\MockClassCodeGenerator;
use PHPMockito\Mock\MockedClass;
use PHPMockito\Mock\MockedMethodListFactory;
use PHPMockito\Mock\MockFactory;
use PHPMockito\ToString\ToStringAdaptorFactory;
use PHPMockito\Verify\MockedMethodCallVerifier;
use PHPMockito\Verify\Verify;

class DependencyFactory implements InitialisationCallListenerFactory, MethodCallListenerFactory {
    const CLASS_NAME = __CLASS__;

    /** @var \PHPMockito\Mock\MockFactory */
    private $mockFactory;

    /** @var \PHPMockito\Expectancy\InitialisationCallRegistrar */
    private $initialisationCallRegistrar;

    /** @var MockedMethodCallVerifier */
    private $mockedMethodCallVerifier;


    function __construct( InitialisationCallRegistrar $initialisationCallRegistrar ) {
        $this->mockFactory = new MockFactory(
            new MockClassCodeGenerator(),
            $this,
            new MockedMethodListFactory(),
            new FileBasedMockedClassCodeLogger(),
            $this->newToStringAdaptorFactory()
        );

        $this->initialisationCallRegistrar = $initialisationCallRegistrar;

        $this->mockedMethodCallVerifier = $this->createMockedMethodCallVerifier();
    }


    /**
     * @return MockedMethodCallVerifier
     */
    private function createMockedMethodCallVerifier() {
        return new MockedMethodCallVerifier( $this->createCallMatcher() );

    }


    /**
     * @return CallMatcher
     */
    private function createCallMatcher() {
        return new CallMatcher( $this->newToStringAdaptorFactory() );
    }


    /**
     * @return ToStringAdaptorFactory
     */
    private function newToStringAdaptorFactory() {
        return new ToStringAdaptorFactory();
    }


    /**
     * @return \PHPMockito\Verify\MockedMethodCallVerifier
     */
    public function getMockedMethodCallVerifier() {
        return $this->mockedMethodCallVerifier;
    }


    /**
     * @return MockFactory
     */
    public function getMockFactory() {
        return $this->mockFactory;
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
            $this->mockedMethodCallVerifier,
            $this->createCallMatcher(),
            $this->createTestCaseCallVerifier()
        );
    }


    /**
     * @return TestCaseCallVerifier
     */
    public function createTestCaseCallVerifier() {
        return new TestCaseCallVerifier(
            array( new PhpUnitTestCaseInitialisationMatcher() )
        );
    }


    /**
     * @param MockedClass $mockedClass
     * @param             $expectedCallCount
     *
     * @return Verify
     */
    public function newVerify( MockedClass $mockedClass, $expectedCallCount ) {
        return new Verify(
            $this->newToStringAdaptorFactory(),
            $this->mockedMethodCallVerifier,
            $mockedClass,
            $expectedCallCount
        );
    }


    /**
     * @param InitialisationCallRegistrar $initialisationCallRegistrar
     * @param ExpectedMethodCall          $methodCall
     *
     * @return MethodCallActionInitialiser
     */
    public function newMethodCallActionInitialiser( InitialisationCallRegistrar $initialisationCallRegistrar,
                                                    ExpectedMethodCall $methodCall ) {
        return new MethodCallActionInitialiser(
            $this->newToStringAdaptorFactory(),
            $initialisationCallRegistrar,
            $methodCall
        );
    }


}
 