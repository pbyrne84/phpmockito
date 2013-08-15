<?php

namespace PHPMockito\Action;


class FullyActionedMethodCall {
    const CLASS_NAME = __CLASS__;

    /** @var MethodCall */
    private $mockedMethod;

    /** @var MethodCallAction */
    private $methodCall;


    /**
     * @param MethodCall       $methodCall
     * @param MethodCallAction $methodCallAction
     */
    function __construct( MethodCall $methodCall, MethodCallAction $methodCallAction ) {
        $this->mockedMethod       = $methodCall;
        $this->methodCall = $methodCallAction;
    }


    /**
     * @return \PHPMockito\Action\MethodCall
     */
    public function getMockedMethod() {
        return $this->mockedMethod;
    }


    /**
     * @return MethodCallAction
     */
    public function getMethodCall() {
        return $this->methodCall;
    }
}
