<?php
namespace PHPMockito\Verify;

use PHPMockito\Action\ExpectedMethodCall;
use PHPMockito\Mock\MockedClass;
use PHPMockito\ToString\ToStringAdaptorFactory;

class Verify {
    
    /** @var VerificationTester */
    private $verificationTester;

    /** @var \PHPMockito\Mock\MockedClass */
    private $mockedClass;

    /** @var int */
    private $expectedCallCount;

    /** @var \PHPMockito\ToString\ToStringAdaptorFactory */
    private $toStringAdaptorFactory;


    /**
     * @param ToStringAdaptorFactory $toStringAdaptorFactory
     * @param VerificationTester     $verificationTester
     * @param MockedClass            $mockedClass
     * @param int                    $expectedCallCount
     */
    function __construct( ToStringAdaptorFactory $toStringAdaptorFactory,
                          VerificationTester $verificationTester,
                          MockedClass $mockedClass,
                          $expectedCallCount ) {
        $this->verificationTester     = $verificationTester;
        $this->mockedClass            = $mockedClass;
        $this->expectedCallCount      = $expectedCallCount;
        $this->toStringAdaptorFactory = $toStringAdaptorFactory;
    }


    function __call( $methodName, $arguments ) {
        $isMagicCallMethodAttempt = false;
        if ( $this->mockedClass->hasMockedMethod( $methodName ) ) {
            $defaultValues = $this->mockedClass->getMethodsDefaultParameterMap( $methodName );
            $arguments     = $arguments + $defaultValues;
        } elseif ( $this->mockedClass->hasMockedMethod( '__call' ) ) {
            $isMagicCallMethodAttempt = true;
            $defaultValues            = $this->mockedClass->getMethodsDefaultParameterMap( '__call' );
            $arguments                = array( $methodName, $arguments ) + $defaultValues;
            $methodName               = '__call';
        } else {
            throw new \InvalidArgumentException( 'Method ' . $methodName . ' does not exist on ' . get_class( $this->mockedClass ) );
        }

        ksort( $arguments );
        $methodCall = new ExpectedMethodCall( $this->toStringAdaptorFactory, $this->mockedClass, $methodName, $arguments );
        $this->verificationTester->assertCallCount(
            $methodCall,
            $this->expectedCallCount,
            $isMagicCallMethodAttempt
        );
    }


}
 