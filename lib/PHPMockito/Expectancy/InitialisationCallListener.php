<?php

namespace PHPMockito\Expectancy;


use PHPMockito\Action\DebugBackTraceMethodCall;

class InitialisationCallListener {
    const CLASS_NAME = __CLASS__;

    /** @var InitialisationCallMatcher[] */
    private $initialisationCallMatcherList;


    /**
     * @param InitialisationCallMatcher[] $InitialisationCallMatcherList
     */
    function __construct( array $InitialisationCallMatcherList ) {
        $this->initialisationCallMatcherList = $InitialisationCallMatcherList;
    }


    /**
     * @param \PHPMockito\Action\DebugBackTraceMethodCall $methodCall
     *
     * @return bool
     */
    public function tryInitialisationRegistration( DebugBackTraceMethodCall $methodCall ) {
        foreach ( $this->initialisationCallMatcherList as $initialisationCallMatcher ) {
            if ( $initialisationCallMatcher->checkIsInitialisationCall( $methodCall->getDebugBacktrace() ) ) {

                return true;
            }
        }

        return false;
    }
}
