<?php

namespace PHPMockito\Verify;


use PHPMockito\Action\MethodCall;

interface VerificationTester {
    const INTERFACE_VerificationTester = __CLASS__;


    /**
     * @param MethodCall $expectedMethodCall
     * @param int        $expectedCallCount
     * @param bool       $isMagicCallMethodAttempt
     *
     * @throws \PHPUnit_Framework_AssertionFailedError - if actual call count is not not equal to expected
     */
    public function assertCallCount( MethodCall $expectedMethodCall, $expectedCallCount, $isMagicCallMethodAttempt );
}
 