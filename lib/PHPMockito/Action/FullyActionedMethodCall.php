<?php

namespace PHPMockito\Action;


use PHPMockito\ToString\ToStringAdaptorFactory;

class FullyActionedMethodCall implements MethodCall {
    const CLASS_NAME = __CLASS__;

    /** @var ExpectedMethodCall */
    private $methodCall;

    /** @var MethodCallAction */
    private $methodCallAction;

    /** @var ToStringAdaptorFactory */
    private $toStringAdaptorFactory;


    /**
     * @param ToStringAdaptorFactory $toStringAdaptorFactory
     * @param ExpectedMethodCall     $methodCall
     * @param MethodCallAction       $methodCallAction
     */
    function __construct( ToStringAdaptorFactory $toStringAdaptorFactory,
                          ExpectedMethodCall $methodCall,
                          MethodCallAction $methodCallAction ) {
        $this->methodCall             = $methodCall;
        $this->methodCallAction       = $methodCallAction;
        $this->toStringAdaptorFactory = $toStringAdaptorFactory;
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


    /**
     * @return string
     */
    function __toString() {
        return print_r( \PHPUnit_Util_Type::toArray( $this ), true );
    }


    /**
     * @return string
     */
    public function convertToString() {
        $arguments = '';
        foreach ( $this->getArguments() as $index => $argument ) {
            $adaptor = $this->toStringAdaptorFactory->createToStringAdaptor( $argument );
            $arguments .= '[' . $index . ']' . $adaptor->toString() ."\n";
        }

        return sprintf( 'arguments(%s)', $arguments );
    }
}
