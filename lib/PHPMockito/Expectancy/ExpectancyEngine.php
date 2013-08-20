<?php

namespace PHPMockito\Expectancy;


use PHPMockito\Action\DebugBackTraceMethodCall;
use PHPMockito\Action\ExceptionMethodCallAction;
use PHPMockito\Action\FullyActionedMethodCall;
use PHPMockito\Action\ReturningMethodCallAction;
use PHPMockito\Caster\ValueCaster;
use PHPMockito\Caster\ValueCasterFactory;

class ExpectancyEngine implements InitialisationCallRegistrar {
    const CLASS_NAME = __CLASS__;

    /** @var array|FullyActionedMethodCall[] */
    private $expectancyList = array();

    /** @var \PHPMockito\Caster\ValueCasterFactory */
    private $valueCasterFactory;


    function __construct( ValueCasterFactory $valueCasterFactory ) {
        $this->valueCasterFactory = $valueCasterFactory;
    }


    public function registerMockMethodExpectancy( FullyActionedMethodCall $fullyActionedMethodCall ) {
        $this->expectancyList[ ] = $fullyActionedMethodCall;
    }


    public function retrieveMockMethodAction( DebugBackTraceMethodCall $methodCall ) {
        foreach ( $this->expectancyList as $expectancy ) {
            if ( $this->matchCall( $methodCall, $expectancy ) ) {
                $methodCallAction = $expectancy->getMethodCallAction();
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


    private function matchCall( DebugBackTraceMethodCall $currentCall, FullyActionedMethodCall $mockedCall ) {
        $mockedMethodCall = $mockedCall->getMockedMethod();
        if ( !$this->runMatch( $currentCall->getClass(), $mockedMethodCall->getClass() ) ) {
            return false;
        }

        if ( $currentCall->getMethod() != $mockedMethodCall->getMethod() ) {
            return false;
        }

        if ( $currentCall->getArgumentCount() != $mockedMethodCall->getArgumentCount() ) {
            return false;
        }

        foreach ( $currentCall->getArguments() as $argumentIndex => $currentArgument ) {
            $currentExpectedArgument = $mockedMethodCall->getArgument( $argumentIndex );
            if ( !$this->runMatch( $currentArgument, $currentExpectedArgument ) ) {
                return false;

            }
        }

        return true;
    }


    private function runMatch( $current, $mocked ) {
        $comparableCurrent = $this->valueCasterFactory->castValueToComparableType( $current );
        $comparableMocked  = $this->valueCasterFactory->castValueToComparableType( $mocked );

        return
                $comparableCurrent->getOriginalType() == $comparableMocked->getOriginalType() &&
                $comparableCurrent->toComparableString() == $comparableMocked->toComparableString();
    }
}
 