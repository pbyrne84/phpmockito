<?php

namespace PHPMockito\Expectancy;


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


    /**
     * @param \PHPMockito\Verify\MockedMethodCallLogger $mockedMethodCallLogger
     * @param \PHPMockito\CallMatching\CallMatcher      $callMatcher
     */
    function __construct( MockedMethodCallLogger $mockedMethodCallLogger, CallMatcher $callMatcher ) {
        $this->callMatcher = $callMatcher;
        $this->mockedMethodCallLogger = $mockedMethodCallLogger;
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
        $this->mockedMethodCallLogger->logMethodCall( $actualProductionMethodCall );

        foreach ( $this->expectedMethodCallList as $expectedMethodCall ) {
            if ( $this->callMatcher->matchCall( $expectedMethodCall, $actualProductionMethodCall ) ) {
                $methodCallAction = $expectedMethodCall->getMethodCallAction();
                if ( $methodCallAction instanceof ExceptionMethodCallAction ) {
                    throw $methodCallAction->getExceptionToBeThrown();
                } elseif ( $methodCallAction instanceof ReturningMethodCallAction ) {
                    return $methodCallAction->getReturnValue();
                }

                throw new \UnexpectedValueException( "Que???" );
            }
        }

        return null;
    }


    private function logCall( MethodCall $methodCall ) {
        $this->callList[ ] = $methodCall;
    }
}
 