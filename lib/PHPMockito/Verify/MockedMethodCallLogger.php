<?php

namespace PHPMockito\Verify;


use PHPMockito\Action\CallableMethod;

interface MockedMethodCallLogger {
    const INTERFACE_MockedMethodCallLogger = __CLASS__;


    /**
     * @param \PHPMockito\Action\CallableMethod $methodCall
     */
    public function logMethodCall( CallableMethod $methodCall );
}
 