<?php

namespace PHPMockito\Run;


use PHPMockito\Action\ExpectedMethodCall;
use PHPMockito\Action\MethodCallActionInitialiser;
use PHPMockito\Action\MethodCallListener;
use PHPMockito\Action\MethodCallListenerFactory;
use PHPMockito\CallMatching\CallMatcher;
use PHPMockito\Expectancy\ExpectancyEngine;
use PHPMockito\Expectancy\InitialisationCallListenerFactoryImpl;
use PHPMockito\Expectancy\InitialisationCallRegistrar;
use PHPMockito\Mock\Logger\NullMockedClassCodeLogger;
use PHPMockito\Mock\MockClassCodeGenerator;
use PHPMockito\Mock\MockedClass;
use PHPMockito\Mock\MockedMethodListFactory;
use PHPMockito\Mock\MockFactory;
use PHPMockito\Signature\SignatureGenerator;
use PHPMockito\ToString\ToStringAdaptorFactory;
use PHPMockito\Verify\MockedMethodCallVerifier;
use PHPMockito\Verify\RuntimeMethodCallLogger;
use PHPMockito\Verify\Verify;

class DependencyFactory implements MethodCallListenerFactory {
    const CLASS_NAME = __CLASS__;
    private $initialisationCallListenerFactoryImpl;

    /** @var \PHPMockito\Verify\RuntimeMethodCallLogger */
    private $runtimeMethodLogger;

    /** @var \PHPMockito\Expectancy\InitialisationCallRegistrar */
    private $initialisationCallRegistrar;

    /** @var MockedMethodCallVerifier */
    private $mockedMethodCallVerifier;


    function __construct() {
        $this->initialisationCallListenerFactoryImpl = new InitialisationCallListenerFactoryImpl();
        $this->runtimeMethodLogger = $this->createRuntimeMethodLogger();
        $this->mockedMethodCallVerifier = $this->createMockedMethodCallVerifier( $this->runtimeMethodLogger );
    }


    public function setInitialisationCallRegistrar( InitialisationCallRegistrar $initialisationCallRegistrar ) {
        $this->initialisationCallRegistrar = $initialisationCallRegistrar;
    }


    /**
     * @return ToStringAdaptorFactory
     */
    private function createToStringAdaptorFactory() {
        return new ToStringAdaptorFactory();
    }


    /**
     * @return RuntimeMethodCallLogger
     */
    private function createRuntimeMethodLogger() {
        $runtimeMethodCallLogger = new RuntimeMethodCallLogger(
                $this->createCallMatcher(),
                $this->createSignatureGenerator()
        );

        return $runtimeMethodCallLogger;
    }


    /**
     * @return CallMatcher
     */
    private function createCallMatcher() {
        return new CallMatcher( $this->createToStringAdaptorFactory() );
    }


    /**
     * @return SignatureGenerator
     */
    private function createSignatureGenerator() {
        $signatureGenerator = new SignatureGenerator( $this->createToStringAdaptorFactory() );

        return $signatureGenerator;
    }


    /**
     * @param \PHPMockito\Verify\RuntimeMethodCallLogger $runtimeMethodCallLogger
     *
     * @return MockedMethodCallVerifier
     */
    private function createMockedMethodCallVerifier( RuntimeMethodCallLogger $runtimeMethodCallLogger ) {
        return new MockedMethodCallVerifier(
                $runtimeMethodCallLogger,
                $this->createSignatureGenerator()
        );
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
        static $mockFactory;

        if ( $mockFactory == null ) {
            $mockFactory = new MockFactory(
                    new MockClassCodeGenerator(),
                    $this,
                    new MockedMethodListFactory(),
                    new NullMockedClassCodeLogger(),
                    $this->createToStringAdaptorFactory()
            );

        }

        return $mockFactory;
    }


    /**
     * @return MethodCallListener
     */
    public function createMethodCallListener() {
        return new MethodCallListener(
                $this->initialisationCallListenerFactoryImpl,
                $this->initialisationCallRegistrar
        );
    }


    /**
     * @return ExpectancyEngine
     */
    public function createExpectancyEngine() {
        return new ExpectancyEngine(
                $this->mockedMethodCallVerifier,
                $this->createCallMatcher(),
                $this->initialisationCallListenerFactoryImpl->createTestCaseCallVerifier()
        );
    }


    /**
     * @param MockedClass $mockedClass
     * @param             $expectedCallCount
     *
     * @return Verify
     */
    public function createVerify( MockedClass $mockedClass, $expectedCallCount ) {
        return new Verify(
                $this->createToStringAdaptorFactory(),
                $this->mockedMethodCallVerifier,
                $mockedClass,
                $expectedCallCount
        );
    }


    /**
     * @param InitialisationCallRegistrar $initialisationCallRegistrar
     * @param ExpectedMethodCall $methodCall
     *
     * @return MethodCallActionInitialiser
     */
    public function createMethodCallActionInitialiser( InitialisationCallRegistrar $initialisationCallRegistrar,
                                                       ExpectedMethodCall $methodCall ) {
        return new MethodCallActionInitialiser(
                $this->createToStringAdaptorFactory(),
                $initialisationCallRegistrar,
                $methodCall
        );
    }


    /**
     * @param string $fullyQualifiedClassName
     */
    public function addIgnorableNonProductionTestClass( $fullyQualifiedClassName ) {
        $this->initialisationCallListenerFactoryImpl->addIgnorableNonProductionTestClass( $fullyQualifiedClassName );
    }


}
 