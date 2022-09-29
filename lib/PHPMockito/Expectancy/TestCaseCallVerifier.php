<?php

namespace PHPMockito\Expectancy;


use PHPMockito\Action\DebugBackTraceMethodCall;
use PHPMockito\Run\RuntimeState;

class TestCaseCallVerifier {
    
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
    public function callIsInTestCase( DebugBackTraceMethodCall $methodCall ) {
        if( $methodCall->getMethod() == '__destruct' ){
            return false;
        }

        foreach ( $this->initialisationCallMatcherList as $initialisationCallMatcher ) {
            if ( $initialisationCallMatcher->checkIsInitialisationCall( $methodCall->getDebugBacktrace() ) ) {
                return true;
            }
        }

        return RuntimeState::getInstance()->getOverrideIsTestCaseCheck();
    }
}
