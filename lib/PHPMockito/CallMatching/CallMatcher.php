<?php

namespace PHPMockito\CallMatching;

use PHPMockito\Action\CallableMethod;
use PHPMockito\ToString\ToStringAdaptorFactory;

class CallMatcher {
    const CLASS_NAME = __CLASS__;

    /** @var ToStringAdaptorFactory */
    private $toStringAdaptorFactory;


    /**
     * @param ToStringAdaptorFactory $toStringAdaptorFactory
     */
    function __construct( ToStringAdaptorFactory $toStringAdaptorFactory ) {
        $this->toStringAdaptorFactory = $toStringAdaptorFactory;
    }


    /**
     * @param CallableMethod $expectedMethodCall
     * @param CallableMethod $actualProductionMethodCall
     *
     * @return bool
     */
    public function matchCallAndSignature( CallableMethod $expectedMethodCall, CallableMethod $actualProductionMethodCall ) {
        return $this->matchCall( $expectedMethodCall, $actualProductionMethodCall )
        && $this->matchSignature( $expectedMethodCall, $actualProductionMethodCall );

    }


    /**
     * @param CallableMethod $expectedMethodCall
     * @param CallableMethod $actualProductionMethodCall
     *
     * @return bool
     */
    public function matchCall( CallableMethod $expectedMethodCall, CallableMethod $actualProductionMethodCall ) {
        if ( !$this->runMatch( $expectedMethodCall->getClass(), $actualProductionMethodCall->getClass() ) ) {
            return false;
        }

        if ( $expectedMethodCall->getMethod() != $actualProductionMethodCall->getMethod() ) {
            return false;
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
        $currentToStringAdaptor = $this->toStringAdaptorFactory->createToStringAdaptor( $current );
        $mockedToStringAdaptor  = $this->toStringAdaptorFactory->createToStringAdaptor( $mocked );

        return $currentToStringAdaptor->toString() == $mockedToStringAdaptor->toString();
    }


    /**
     * @param CallableMethod $expectedMethodCall
     * @param CallableMethod $actualProductionMethodCall
     *
     * @return bool
     */
    public function matchSignature( CallableMethod $expectedMethodCall, CallableMethod $actualProductionMethodCall ) {
        if ( $expectedMethodCall->getArgumentCount() != $actualProductionMethodCall->getArgumentCount() ) {
            return false;
        }

        foreach ( $expectedMethodCall->getArguments() as $argumentIndex => $expectedArgument ) {
            $currentExpectedArgument = $actualProductionMethodCall->getArgument( $argumentIndex );
            if ( !$this->runMatch( $expectedArgument, $currentExpectedArgument ) ) {
                return false;

            }
        }

        return true;
    }
}
