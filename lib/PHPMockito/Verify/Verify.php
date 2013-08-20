<?php
namespace PHPMockito\Verify;

use PHPMockito\Action\ExpectedMethodCall;
use PHPMockito\Mock\MockedClass;

class Verify {
    const CLASS_NAME = __CLASS__;

    /** @var VerificationTester */
    private $verificationTester;

    /** @var \PHPMockito\Mock\MockedClass */
    private $mockedClass;

    /** @var int */
    private $expectedCallCount;


    /**
     * @param VerificationTester $verificationTester
     * @param MockedClass        $mockedClass
     * @param int                $expectedCallCount
     */
    function __construct( VerificationTester $verificationTester, MockedClass $mockedClass, $expectedCallCount ) {
        $this->verificationTester = $verificationTester;
        $this->mockedClass        = $mockedClass;
        $this->expectedCallCount  = $expectedCallCount;
    }


    function __call( $name, $arguments ) {
        $methodCall = new ExpectedMethodCall( $this->mockedClass, $name, $arguments );
        $this->verificationTester->assertCallCount(  $methodCall, $this->expectedCallCount );

    }


}
 