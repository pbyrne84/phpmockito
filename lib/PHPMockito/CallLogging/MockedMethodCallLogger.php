<?php
namespace PHPMockito\CallLogging;

use PHPMockito\Action\ExpectedMethodCall;
use PHPMockito\CallMatching\CallMatcher;

class MockedMethodCallLogger {
    const CLASS_NAME = __CLASS__;

    /** @var ExpectedMethodCall[] */
    private $actualMethodCallList = array();

    /** @var CallMatcher */
    private $callMatcher;


    /**
     * @param CallMatcher $callMatcher
     */
    function __construct( CallMatcher $callMatcher ) {
        $this->callMatcher = $callMatcher;
    }


    /**
     * @param ExpectedMethodCall $methodCall
     */
    public function logMethodCall( ExpectedMethodCall $methodCall ) {
        $this->actualMethodCallList[ ] = $methodCall;
    }


    /**
     * @param ExpectedMethodCall $expectedMethodCall
     * @param int        $expectedCallCount
     */
    public function assertCallCount( ExpectedMethodCall $expectedMethodCall, $expectedCallCount ) {
        $callCall = 0;
        foreach ( $this->actualMethodCallList as $actualMethodCall ) {
            if ( $this->callMatcher->matchCall( )) {
            }
        }
    }


}
 