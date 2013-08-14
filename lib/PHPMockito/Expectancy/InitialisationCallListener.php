<?php

namespace PHPMockito\Expectancy;


use PHPMockito\Action\MethodCall;

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
     * @param \PHPMockito\Action\MethodCall $methodCall
     *
     * @return bool
     */
    public function tryInitialisationRegistration( MethodCall $methodCall ) {
        foreach ( $this->initialisationCallMatcherList as $initialisationCallMatcher ) {
            if ( $initialisationCallMatcher->checkIsInitialisationCall( $methodCall->getDebugBacktrace() ) ) {

                return true;
            }
        }

        return false;
    }
}
