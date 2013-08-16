<?php

namespace PHPMockito\Expectancy;


use PHPMockito\Action\DebugBackTraceMethodCall;
use PHPMockito\Action\ExceptionMethodCallAction;
use PHPMockito\Action\FullyActionedMethodCall;
use PHPMockito\Action\ReturningMethodCallAction;

class ExpectancyEngine implements InitialisationCallRegistrar {
    const CLASS_NAME = __CLASS__;

    /** @var array|FullyActionedMethodCall[] */
    private $expectancyList = array();


    function __construct() {
    }


    public function registerMockMethodExpectancy( FullyActionedMethodCall $fullyActionedMethodCall ) {
        $this->expectancyList[ ] = $fullyActionedMethodCall;
    }


    public function retrieveMockMethodAction( DebugBackTraceMethodCall $methodCall ) {
        foreach ( $this->expectancyList as $expectancy ) {
            $expectedMethodCall = $expectancy->getMockedMethod();
            $expectedMethodCall->getClass();
            $isCorrectClass     = $methodCall->getClass() == $expectedMethodCall->getClass();
            $isCorrectMethod    = $methodCall->getMethod() == $expectedMethodCall->getMethod();
            $isCorrectArguments = $methodCall->getArguments() == $expectedMethodCall->getArguments();

            if ( $isCorrectClass && $isCorrectMethod && $isCorrectArguments ) {
                $methodCallAction = $expectancy->getMethodCallAction();
                if( $methodCallAction instanceof ExceptionMethodCallAction ){
                    throw $methodCallAction->getExceptionToBeThrown();
                }elseif ( $methodCallAction instanceof ReturningMethodCallAction ){
                    return $methodCallAction->getReturnValue();
                }

                throw new \UnexpectedValueException("Que???");
            }
        }

        return null;
    }
}
 