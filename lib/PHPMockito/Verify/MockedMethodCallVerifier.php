<?php
namespace PHPMockito\Verify;

use PHPMockito\Action\CallableMethod;
use PHPMockito\Mock\MockedClass;
use PHPMockito\Signature\SignatureGenerator;

class MockedMethodCallVerifier implements MockedMethodCallLogger, VerificationTester {
    const CLASS_NAME = __CLASS__;

    /** @var RuntimeMethodCallLogger */
    private $runtimeMethodCallLogger;

    /** @var \PHPMockito\Signature\SignatureGenerator */
    private $signatureGenerator;


    /**
     * @param RuntimeMethodCallLogger $runtimeMethodCallLogger
     * @param SignatureGenerator      $signatureGenerator
     */
    function __construct( RuntimeMethodCallLogger $runtimeMethodCallLogger, SignatureGenerator $signatureGenerator ) {
        $this->runtimeMethodCallLogger = $runtimeMethodCallLogger;
        $this->signatureGenerator      = $signatureGenerator;
    }


    /**
     * @param CallableMethod $methodCall
     */
    public function logMethodCall( CallableMethod $methodCall ) {
        $this->runtimeMethodCallLogger->logMethodCall( $methodCall );
    }


    /**
     * @param CallableMethod $expectedMethodCall
     * @param int        $expectedCallCount
     * @param bool       $isMagicCallMethodAttempt
     *
     * @throws \PHPUnit_Framework_AssertionFailedError - if actual call count is not not equal to expected
     */
    public function assertCallCount( CallableMethod $expectedMethodCall, $expectedCallCount, $isMagicCallMethodAttempt ) {
        $actualCallLoggingStatus = $this->runtimeMethodCallLogger->getMethodCallLoggingStatus( $expectedMethodCall );

        $actualCallCount = $actualCallLoggingStatus->getCount();
        if ( $actualCallCount == $expectedCallCount ) {
            return;
        }

        $expectedMessage =
                'Expected a call of count ' . $expectedCallCount . ' got ' . $actualCallCount . PHP_EOL .
                $this->generateHeaderMessage( $isMagicCallMethodAttempt ) . "\n\n" .
                "*** Call expected $expectedCallCount time/s: ***" . PHP_EOL .
                $this->signatureGenerator->generateMessage( $expectedMethodCall );

        $message = $expectedMessage . "\n" .
                "*** Actual calls :- ***\n" . $actualCallLoggingStatus->getAllCallsMessage() . PHP_EOL . PHP_EOL;

        $this->raiseAssertionError(
            $message,
            $expectedMethodCall
        );
    }


    /**
     * @param boolean $isMagicCallMethodAttempt
     *
     * @return string
     */
    private function generateHeaderMessage( $isMagicCallMethodAttempt ) {
        $startOffset = 4;
        if ( $isMagicCallMethodAttempt ) {
            $startOffset = 3;
        }

        $debug_backtrace = debug_backtrace();

        $testMethodName = $debug_backtrace[ $startOffset ][ 'function' ];
        $file           = $debug_backtrace[ $startOffset - 1 ][ 'file' ];
        $line           = $debug_backtrace[ $startOffset - 1 ][ 'line' ];

        return '*** Test:' . $testMethodName . " ***\n" .
        $file . '(' . $line . ')';
    }


    /**
     * @param string     $baseMessage
     * @param CallableMethod $expectedMethodCal
     *
     * @throws \PHPUnit_Framework_AssertionFailedError
     */
    private function raiseAssertionError( $baseMessage, CallableMethod $expectedMethodCal ) {
        throw new \PHPUnit_Framework_AssertionFailedError( $baseMessage );
    }


    /**
     * @param \PHPMockito\Mock\MockedClass $mockedClass
     *
     * @throws \PHPUnit_Framework_AssertionFailedError
     */
    public function verifyNoMoreInteractions( MockedClass $mockedClass ) {
        $unverifiedMethodCalls = $this->runtimeMethodCallLogger->getUnverifiedMethodCalls( $mockedClass );
        if ( $unverifiedMethodCalls == '' ) {
            return;
        }

        throw new \PHPUnit_Framework_AssertionFailedError( $unverifiedMethodCalls );
    }
}
 