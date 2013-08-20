<?php

namespace PHPMockito\CallMatching;
use PHPMockito\Action\MethodCall;
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
     * @param MethodCall $expectedMethodCall
     * @param MethodCall  $actualProductionMethodCall
     *
     * @return bool
     */
    public function matchCall( MethodCall $expectedMethodCall, MethodCall $actualProductionMethodCall ) {
        if ( !$this->runMatch( $expectedMethodCall->getClass(), $actualProductionMethodCall->getClass() ) ) {
            return false;
        }

        if ( $expectedMethodCall->getMethod() != $actualProductionMethodCall->getMethod() ) {
            return false;
        }

        if ( $expectedMethodCall->getArgumentCount() != $actualProductionMethodCall->getArgumentCount() ) {
            return false;
        }

        foreach ( $expectedMethodCall->getArguments() as $argumentIndex => $currentArgument ) {
            $currentExpectedArgument = $actualProductionMethodCall->getArgument( $argumentIndex );
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
 