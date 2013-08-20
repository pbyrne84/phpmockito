<?php

namespace PHPMockito\CallMatching;
use PHPMockito\Action\DebugBackTraceMethodCall;
use PHPMockito\Action\FullyActionedMethodCall;
use PHPMockito\Caster\ValueCasterFactory;

class CallMatcher {
    const CLASS_NAME = __CLASS__;

    /** @var ValueCasterFactory */
    private $valueCasterFactory;


    /**
     * @param ValueCasterFactory $valueCasterFactory
     */
    function __construct( ValueCasterFactory $valueCasterFactory) {
        $this->valueCasterFactory = $valueCasterFactory;
    }


    /**
     * @param DebugBackTraceMethodCall $currentCall
     * @param FullyActionedMethodCall  $mockedCall
     *
     * @return bool
     */
    public function matchCall( DebugBackTraceMethodCall $currentCall, FullyActionedMethodCall $mockedCall ) {
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


    /**
     * @param mixed $current
     * @param mixed $mocked
     *
     * @return bool
     */
    private function runMatch( $current, $mocked ) {
        $comparableCurrent = $this->valueCasterFactory->castValueToComparableType( $current );
        $comparableMocked  = $this->valueCasterFactory->castValueToComparableType( $mocked );

        return
                $comparableCurrent->getOriginalType() == $comparableMocked->getOriginalType() &&
                $comparableCurrent->toComparableString() == $comparableMocked->toComparableString();
    }
}
 