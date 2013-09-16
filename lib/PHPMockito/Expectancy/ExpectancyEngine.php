<?php

namespace PHPMockito\Expectancy;


use PHPMockito\Action\DebugBackTraceMethodCall;
use PHPMockito\Action\ExceptionMethodCallAction;
use PHPMockito\Action\FullyActionedMethodCall;
use PHPMockito\Action\MethodCall;
use PHPMockito\Action\MethodCallAction;
use PHPMockito\Action\ReturningMethodCallAction;
use PHPMockito\CallMatching\CallMatcher;
use PHPMockito\Run\RuntimeState;
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

        $methodCallAction = $this->findRegisteredCallAction( $actualProductionMethodCall );
        if ( $methodCallAction instanceof MethodCallAction ) {
            return $this->executeCallAction( $methodCallAction );
        }


        if ( RuntimeState::getInstance()
                        ->isStrictMode() && $isProductionMethodCall
        ) {
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
     * @param MethodCall $actualProductionMethodCall
     *
     * @return null|MethodCallAction
     */
    private function findRegisteredCallAction( MethodCall $actualProductionMethodCall ) {
        foreach ( $this->expectedMethodCallList as $expectedMethodCall ) {
            if ( $this->callMatcher->matchCallAndSignature( $expectedMethodCall, $actualProductionMethodCall ) ) {
                return $expectedMethodCall->getMethodCallAction();
            }
        }

        return null;
    }


    /**
     * @param $methodCallAction
     *
     * @return mixed
     * @throws \Exception
     * @throws \UnexpectedValueException
     */
    private function executeCallAction( $methodCallAction ) {
        if ( $methodCallAction instanceof ExceptionMethodCallAction ) {
            throw $methodCallAction->getExceptionToBeThrown();
        } elseif ( $methodCallAction instanceof ReturningMethodCallAction ) {
            return $methodCallAction->getReturnValue();
        }

        throw new \UnexpectedValueException( "Que???" );
    }


    /**
     * @param MethodCall $actualProductionMethodCall
     *
     * @return bool
     */
    public function hasMockMethodAction( MethodCall $actualProductionMethodCall ) {
        return !$this->isProductionMethodCall( $actualProductionMethodCall )
         ||  null !== $this->findRegisteredCallAction( $actualProductionMethodCall );
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
