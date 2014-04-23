<?php

namespace PHPMockito\Verify;


use PHPMockito\Action\DebugBackTraceMethodCall;
use PHPMockito\Action\ExpectedMethodCall;
use PHPMockito\Action\CallableMethod;
use PHPMockito\CallMatching\CallMatcher;
use PHPMockito\Mock\MockedClass;
use PHPMockito\Signature\SignatureGenerator;

class RuntimeMethodCallLogger implements MockedMethodCallLogger {
    const CLASS_NAME = __CLASS__;

    /** @var ExpectedMethodCall[] */
    private $actualMethodCallList = array();

    /** @var CallMatcher */
    private $callMatcher;

    /** @var SignatureGenerator */
    private $signatureGenerator;

    /** @var array MethodCall */
    private $verifiedMethodCalls = array();


    /**
     * @param CallMatcher        $callMatcher
     * @param SignatureGenerator $signatureGenerator
     */
    function __construct( CallMatcher $callMatcher, SignatureGenerator $signatureGenerator ) {
        $this->callMatcher        = $callMatcher;
        $this->signatureGenerator = $signatureGenerator;
    }


    /**
     * @param \PHPMockito\Action\CallableMethod $methodCall
     */
    public function logMethodCall( CallableMethod $methodCall ) {
        if ( $methodCall instanceof DebugBackTraceMethodCall ) {
            $this->actualMethodCallList[ ] = $methodCall->castToMethodCall();
        } else {
            $this->actualMethodCallList[ ] = $methodCall;
        }
    }


    /**
     * @param CallableMethod $expectedMethodCall
     *
     * @return \PHPMockito\Verify\MethodCallLoggingStatus
     */
    public function getMethodCallLoggingStatus( CallableMethod $expectedMethodCall ) {
        $actualCallCount   = 0;
        $actualCallMessage = '';
        foreach ( $this->actualMethodCallList as $actualMethodCall ) {
            if ( $this->callMatcher->matchCall( $actualMethodCall, $expectedMethodCall ) ) {
                $methodSignature = $this->signatureGenerator->generateMessage( $actualMethodCall );

                if ( $actualMethodCall->getHashedSignature() == $expectedMethodCall->getHashedSignature()  ) {
                    $this->verifiedMethodCalls[ ] = $expectedMethodCall;
                    $actualCallCount++;
                }

                $actualCallMessage .= $methodSignature . PHP_EOL;
            }
        }

        return new MethodCallLoggingStatus( $expectedMethodCall, $actualCallCount, $actualCallMessage );
    }


    /**
     * @param MockedClass $mockedClass
     *
     * @return string
     */
    public function getUnverifiedMethodCalls( MockedClass $mockedClass ) {
        $noMoreInteractionsCalculator = new NoMoreInteractionsCalculator( $this->signatureGenerator );

        return $noMoreInteractionsCalculator->calculateNonVerifiedInteractions(
            $this->actualMethodCallList,
            $this->verifiedMethodCalls,
            $mockedClass
        );
    }
}
 