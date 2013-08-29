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
            new FileBasedMockedClassCodeLogger()
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
        return new CallMatcher( new ToStringAdaptorFactory() );
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
        return new ExpectancyEngine( $this->mockedMethodCallVerifier, $this->createCallMatcher() );
    }


    /**
     * @param MockedClass $mockedClass
     * @param             $expectedCallCount
     *
     * @return Verify
     */
    public function newVerify( MockedClass $mockedClass, $expectedCallCount ) {
        return new Verify( $this->mockedMethodCallVerifier, $mockedClass, $expectedCallCount );
    }


}
 