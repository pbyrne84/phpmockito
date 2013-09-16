<?php

namespace PHPMockito\Expectancy;


use PHPMockito\Action\DebugBackTraceMethodCall;
use PHPMockito\Action\ExceptionMethodCallAction;
use PHPMockito\Action\ExpectedMethodCall;
use PHPMockito\Action\FullyActionedMethodCall;
use PHPMockito\Action\MethodCall;
use PHPMockito\Action\MethodCallAction;
use PHPMockito\Action\ReturningMethodCallAction;
use PHPMockito\CallMatching\CallMatcher;
use PHPMockito\Run\RuntimeState;
use PHPMockito\Verify\MockedMethodCallLogger;

class ExpectancyEngine implements InitialisationCallRegistrar {
    const CLASS_NAME = __CLASS__;

    /** @var FullyActionedMethodCall[] */
    private $expectedMethodCallMap = array();

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
        $convertMethodCallToMapKey = $this->convertMethodCallToMapKey( $fullyActionedMethodCall );
        if ( !isset( $this->expectedMethodCallMap [ $convertMethodCallToMapKey ] ) ) {
            $this->expectedMethodCallMap[ $convertMethodCallToMapKey ] = array();
        }

        $this->expectedMethodCallMap[ $convertMethodCallToMapKey ][] = $fullyActionedMethodCall;
    }


    private function convertMethodCallToMapKey( MethodCall $methodCall  ){
        return $methodCall->getClass()->getInstanceReference() . '->' . $methodCall->getMethod();
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
        $methodCallToMapKey = $this->convertMethodCallToMapKey( $actualProductionMethodCall );
        if( !isset( $this->expectedMethodCallMap[ $methodCallToMapKey ]) ){
            return null;
        }


        /** @var $expectedMethodCall FullyActionedMethodCall */
        foreach ( $this->expectedMethodCallMap[ $methodCallToMapKey ] as $expectedMethodCall ) {
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
     * @param ExpectedMethodCall $methodCall
     */
    public function registerLatestInitialisationSignature( ExpectedMethodCall $methodCall = null ) {
        $this->lastMethodCall = $methodCall;
    }


    /**
     * @return ExpectedMethodCall
     */
    public function getLastInitialisationMethodCall() {
        return $this->lastMethodCall;
    }


    private function logCall( MethodCall $methodCall ) {
        $this->callList[ ] = $methodCall;
    }
}
