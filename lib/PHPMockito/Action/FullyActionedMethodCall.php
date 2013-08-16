<?php

namespace PHPMockito\Action;


class FullyActionedMethodCall {
    const CLASS_NAME = __CLASS__;

    /** @var DebugBackTraceMethodCall */
    private $mockedMethod;

    /** @var MethodCallAction */
    private $methodCallAction;


    /**
     * @param MethodCall       $methodCall
     * @param MethodCallAction $methodCallAction
     */
    function __construct( MethodCall $methodCall, MethodCallAction $methodCallAction ) {
        $this->mockedMethod     = $methodCall;
        $this->methodCallAction = $methodCallAction;
    }


    /**
     * @return \PHPMockito\Action\DebugBackTraceMethodCall
     */
    public function getMockedMethod() {
        return $this->mockedMethod;
    }


    /**
     * @return MethodCallAction
     */
    public function getMethodCallAction() {
        return $this->methodCallAction;
    }
}
