<?php
namespace PHPMockito\Verify;

use PHPMockito\Action\DebugBackTraceMethodCall;
use PHPMockito\Action\ExpectedMethodCall;
use PHPMockito\Action\MethodCall;
use PHPMockito\CallMatching\CallMatcher;

class MockedMethodCallVerifier implements MockedMethodCallLogger, VerificationTester{
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
     * @param MethodCall $methodCall
     */
    public function logMethodCall( MethodCall $methodCall ) {
        if ( $methodCall instanceof DebugBackTraceMethodCall ) {
            $this->actualMethodCallList[ ] = $methodCall->castToMethodCall();
        }else{
            $this->actualMethodCallList[ ] = $methodCall;
        }
    }


    /**
     * @param MethodCall $expectedMethodCall
     * @param int        $expectedCallCount
     *
     * @throws \PHPUnit_Framework_AssertionFailedError - if actual call count is not not equal to expected
     */
    public function assertCallCount( MethodCall $expectedMethodCall, $expectedCallCount ) {
        $actualCallCount = 0;
        foreach ( $this->actualMethodCallList as $actualMethodCall ) {
            if ( $this->callMatcher->matchCall( $actualMethodCall, $expectedMethodCall )) {
                $actualCallCount++;
            }
        }

        if ( $actualCallCount != $expectedCallCount ) {
            $this->raiseAssertionError(
                'Expected a call of ' . $expectedCallCount . ' got ' . $actualCallCount,
                $expectedMethodCall
            );
        }
    }


    private function raiseAssertionError( $baseMessage, MethodCall $expectedMethodCal  ) {
        throw new \PHPUnit_Framework_AssertionFailedError( $baseMessage );
    }


}
 