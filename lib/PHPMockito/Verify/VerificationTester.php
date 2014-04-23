<?php

namespace PHPMockito\Verify;


use PHPMockito\Action\CallableMethod;

interface VerificationTester {
    const INTERFACE_VerificationTester = __CLASS__;


    /**
     * @param CallableMethod $expectedMethodCall
     * @param int        $expectedCallCount
     * @param bool       $isMagicCallMethodAttempt
     *
     * @throws \PHPUnit_Framework_AssertionFailedError - if actual call count is not not equal to expected
     */
    public function assertCallCount( CallableMethod $expectedMethodCall, $expectedCallCount, $isMagicCallMethodAttempt );
}
 