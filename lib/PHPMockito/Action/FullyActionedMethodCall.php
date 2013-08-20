<?php

namespace PHPMockito\Action;


class FullyActionedMethodCall implements MethodCall {
    const CLASS_NAME = __CLASS__;

    /** @var ExpectedMethodCall */
    private $methodCall;

    /** @var MethodCallAction */
    private $methodCallAction;


    /**
     * @param ExpectedMethodCall $methodCall
     * @param MethodCallAction   $methodCallAction
     */
    function __construct( ExpectedMethodCall $methodCall, MethodCallAction $methodCallAction ) {
        $this->methodCall       = $methodCall;
        $this->methodCallAction = $methodCallAction;
    }


    /**
     * @return ExpectedMethodCall
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


    /**
     * @return int
     */
    public function getArgumentCount() {
        return $this->methodCall->getArgumentCount();
    }


    /**
     * @return array
     */
    public function getArguments() {
        return $this->methodCall->getArguments();
    }


    /**
     * @param $index
     *
     * @return mixed
     * @throws \OutOfRangeException
     */
    public function getArgument( $index ) {
        return $this->methodCall->getArgument( $index );
    }


    /**
     * @return \PHPMockito\Mock\MockedClass
     */
    public function getClass() {
       return $this->methodCall->getClass();
    }


    /**
     * @return string
     */
    public function getMethod() {
        return $this->methodCall->getMethod();
    }
}
