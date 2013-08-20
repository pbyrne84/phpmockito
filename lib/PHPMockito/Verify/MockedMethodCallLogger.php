<?php

namespace PHPMockito\Verify;


use PHPMockito\Action\MethodCall;

interface MockedMethodCallLogger {
    const INTERFACE_MockedMethodCallLogger = __CLASS__;


    /**
     * @param \PHPMockito\Action\MethodCall $methodCall
     */
    public function logMethodCall( MethodCall $methodCall );
}
 