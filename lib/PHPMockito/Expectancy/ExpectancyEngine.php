<?php

namespace PHPMockito\Expectancy;


use PHPMockito\Action\DebugBackTraceMethodCall;
use PHPMockito\Action\ExceptionMethodCallAction;
use PHPMockito\Action\FullyActionedMethodCall;
use PHPMockito\Action\MethodCall;
use PHPMockito\Action\ReturningMethodCallAction;
use PHPMockito\CallMatching\CallMatcher;
use PHPMockito\Verify\MockedMethodCallLogger;

class ExpectancyEngine implements InitialisationCallRegistrar {
    const CLASS_NAME = __CLASS__;

    /** @var array|FullyActionedMethodCall[] */
    private $expectedMethodCallList = array();

    private $callList = array();

    /** @var CallMatcher */
    private $callMatcher;

    /** @var \PHPMockito\Verify\MockedMethodCallLogger */
    private $mockedMethodCallLogger;

    /** @var  MethodCall */
    private $lastMethodCall;

    /** @var TestCaseCallVerifier */
    private $testCaseCallVerifier;


    /**
     * @param \PHPMockito\Verify\MockedMethodCallLogger $mockedMethodCallLogger
     * @param \PHPMockito\CallMatching\CallMatcher      $callMatcher
     * @param TestCaseCallVerifier                      $testCaseCallVerifier
     */
    function __construct( MockedMethodCallLogger $mockedMethodCallLogger,
                          CallMatcher $callMatcher,
                          TestCaseCallVerifier $testCaseCallVerifier ) {
        $this->callMatcher            = $callMatcher;
        $this->mockedMethodCallLogger = $mockedMethodCallLogger;
        $this->testCaseCallVerifier   = $testCaseCallVerifier;
    }


    /**
     * @param FullyActionedMethodCall $fullyActionedMethodCall
     */
    public function registerMockMethodExpectancy( FullyActionedMethodCall $fullyActionedMethodCall ) {
        $this->expectedMethodCallList[ ] = $fullyActionedMethodCall;
    }


    /**
     * @param \PHPMockito\Action\MethodCall $actualProductionMethodCall
     *
     * @throws \Exception - if exception has been set as response
     * @throws \UnexpectedValueException
     * @return mixed|null - if has been set as response
     */
    public function retrieveMockMethodAction( MethodCall $actualProductionMethodCall ) {
        $isProductionMethodCall = $this->isProductionMethodCall( $actualProductionMethodCall );
        if ( $isProductionMethodCall ) {
            $this->mockedMethodCallLogger->logMethodCall( $actualProductionMethodCall );
        }

        foreach ( $this->expectedMethodCallList as $expectedMethodCall ) {
            if ( $this->callMatcher->matchCallAndSignature( $expectedMethodCall, $actualProductionMethodCall ) ) {
                $methodCallAction = $expectedMethodCall->getMethodCallAction();
                if ( $methodCallAction instanceof ExceptionMethodCallAction ) {
                    throw $methodCallAction->getExceptionToBeThrown();
                } elseif ( $methodCallAction instanceof ReturningMethodCallAction ) {
                    return $methodCallAction->getReturnValue();
                }

                throw new \UnexpectedValueException( "Que???" );
            }
        }

        if ( $isProductionMethodCall ) {
            throw new UnexpectedCallException(
                "Unexpected call " . $actualProductionMethodCall
                        ->getClass()
                        ->getInstanceReference() .
                "->" . $actualProductionMethodCall->getMethod()
            );
        }

        return null;

    }


    /**
     * @param MethodCall $actualProductionMethodCall
     *
     * @return bool
     */
    private function isProductionMethodCall( MethodCall $actualProductionMethodCall ) {
        return $actualProductionMethodCall instanceof DebugBackTraceMethodCall &&
        !$this->testCaseCallVerifier->callIsInTestCase( $actualProductionMethodCall );
    }


    /**
     * @param MethodCall $methodCall
     */
    public function registerLatestInitialisationSignature( MethodCall $methodCall = null ) {
        $this->lastMethodCall = $methodCall;
    }


    /**
     * @return MethodCall
     */
    public function getLastInitialisationMethodCall() {
        return $this->lastMethodCall;
    }


    private function logCall( MethodCall $methodCall ) {
        $this->callList[ ] = $methodCall;
    }
}
