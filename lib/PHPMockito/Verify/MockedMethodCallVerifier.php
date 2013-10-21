<?php
namespace PHPMockito\Verify;

use PHPMockito\Action\DebugBackTraceMethodCall;
use PHPMockito\Action\ExpectedMethodCall;
use PHPMockito\Action\MethodCall;
use PHPMockito\CallMatching\CallMatcher;
use PHPMockito\Mock\MockedClass;
use PHPMockito\Output\ValueOutputExporter;
use PHPMockito\Signature\SignatureGenerator;

class MockedMethodCallVerifier implements MockedMethodCallLogger, VerificationTester {
    const CLASS_NAME = __CLASS__;


    /** @var RuntimeMethodCallLogger */
    private $runtimeMethodCallLogger;

    /** @var \PHPMockito\Signature\SignatureGenerator */
    private $signatureGenerator;


    function __construct( RuntimeMethodCallLogger $runtimeMethodCallLogger, SignatureGenerator $signatureGenerator  ) {
        $this->runtimeMethodCallLogger = $runtimeMethodCallLogger;
        $this->signatureGenerator = $signatureGenerator;
    }


    /**
     * @param MethodCall $methodCall
     */
    public function logMethodCall( MethodCall $methodCall ) {
        $this->runtimeMethodCallLogger->logMethodCall( $methodCall );
    }


    /**
     * @param MethodCall $expectedMethodCall
     * @param int        $expectedCallCount
     *
     * @throws \PHPUnit_Framework_AssertionFailedError - if actual call count is not not equal to expected
     */
    public function assertCallCount( MethodCall $expectedMethodCall, $expectedCallCount ) {
        $actualCallLoggingStatus = $this->runtimeMethodCallLogger->getMethodCallLoggingStatus( $expectedMethodCall );

        $actualCallCount = $actualCallLoggingStatus->getCount();
        if ( $actualCallCount == $expectedCallCount ) {
            return;
        }

        $expectedMessage =
                'Expected a call of count ' . $expectedCallCount . ' got ' . $actualCallCount . PHP_EOL .
                $this->generateHeaderMessage() . "\n\n" .
                "*** Call expected $expectedCallCount time/s: ***" . PHP_EOL .
                $this->signatureGenerator->generateMessage( $expectedMethodCall );

        $message = $expectedMessage . "\n" .
                "*** Actual calls :- ***\n" . $actualCallLoggingStatus->getAllCallsMessage() . PHP_EOL . PHP_EOL;

        $this->raiseAssertionError(
            $message,
            $expectedMethodCall
        );
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


    /**
     * @param $mockedClass
     */
    public function verifyNoMoreInteractions( MockedClass $mockedClass ) {
    }


}
 