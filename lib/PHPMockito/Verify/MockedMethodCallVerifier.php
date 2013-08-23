<?php
namespace PHPMockito\Verify;

use PHPMockito\Action\DebugBackTraceMethodCall;
use PHPMockito\Action\ExpectedMethodCall;
use PHPMockito\Action\MethodCall;
use PHPMockito\CallMatching\CallMatcher;

class MockedMethodCallVerifier implements MockedMethodCallLogger, VerificationTester {
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
        } else {
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
        $actualCallCount   = 0;
        $actualCallMessage = '';
        foreach ( $this->actualMethodCallList as $actualMethodCall ) {
            $actualCallMessage .= $this->generateMessage( $actualMethodCall ) . "\n";
            if ( $this->callMatcher->matchCall( $actualMethodCall, $expectedMethodCall ) ) {
                $actualCallCount++;
            }
        }

        if ( $actualCallCount == $expectedCallCount ) {
            return;
        }


        $expectedMessage =
                'Expected a call of count ' . $expectedCallCount . ' got ' . $actualCallCount . "\n" .
                $this->generateHeaderMessage() . "\n\n" .
                "*** Call expected $expectedCallCount time/s: ***\n" .
                $this->generateMessage( $expectedMethodCall );

        $message = $expectedMessage . "\n" .
                "*** Actual calls :- ***\n" . $actualCallMessage;

        $this->raiseAssertionError(
            $message,
            $expectedMethodCall
        );
    }


    private function generateMessage( MethodCall $methodCall ) {
        $methodSignature = get_class( $methodCall->getClass() ) . '->' . $methodCall->getMethod() . "()\n";
        foreach ( $methodCall->getArguments() as $index => $argument ) {
            $methodSignature .= '   arg[' . $index . '] = ' . var_export( $argument, true ) . "\n";
        }

        return $methodSignature;
    }


    private function generateHeaderMessage() {
        $debug_backtrace = debug_backtrace();

        $testMethodName = $debug_backtrace[ 4 ][ 'function' ];
        $file           = $debug_backtrace[ 3 ][ 'file' ];
        $line           = $debug_backtrace[ 3 ][ 'line' ];

        return '*** Test:' . $testMethodName . " ***\n" .
        $file . '(' . $line . ')';


    }


    private function raiseAssertionError( $baseMessage, MethodCall $expectedMethodCal ) {
        throw new \PHPUnit_Framework_AssertionFailedError( $baseMessage );
    }


}
 