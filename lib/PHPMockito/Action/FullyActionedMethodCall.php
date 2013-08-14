<?php

namespace PHPMockito\Action;


class FullyActionedMethodCall {
    const CLASS_NAME = __CLASS__;

    /** @var MethodCall */
    private $methodCall;

    /** @var MethodCallAction */
    private $methodCallAction;


    /**
     * @param MethodCall       $methodCall
     * @param MethodCallAction $methodCallAction
     */
    function __construct( MethodCall $methodCall, MethodCallAction $methodCallAction ) {
        $this->methodCall       = $methodCall;
        $this->methodCallAction = $methodCallAction;
    }


    /**
     * @return \PHPMockito\Action\MethodCall
     */
    public function getMethodCall() {
        return $this->methodCall;
    }


    /**
     * @return MethodCallAction
     */
    public function getMethodCallAction() {
        return $this->methodCallAction;
    }
}
