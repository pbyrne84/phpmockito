<?php

namespace PHPMockito\Action;


use PHPMockito\ToString\ToStringAdaptorFactory;

class FullyActionedMethodCall extends MethodCall implements MethodCallAction {
    
    /** @var ExpectedMethodCall */
    private $methodCall;

    /** @var MethodCallAction */
    private $methodCallAction;

    /** @var ToStringAdaptorFactory */
    private $toStringAdaptorFactory;

    /** @var MethodCallActionInitialiser */
    private $methodCallInitialiser;


    /**
     * @param ToStringAdaptorFactory      $toStringAdaptorFactory
     * @param ExpectedMethodCall          $methodCall
     * @param MethodCallAction            $methodCallAction
     * @param MethodCallActionInitialiser $methodCallInitialiser
     */
    function __construct( ToStringAdaptorFactory $toStringAdaptorFactory,
                          ExpectedMethodCall $methodCall,
                          MethodCallAction $methodCallAction,
                          MethodCallActionInitialiser $methodCallInitialiser ) {

        parent::__construct( $toStringAdaptorFactory );

        $this->methodCall             = $methodCall;
        $this->methodCallAction       = $methodCallAction;
        $this->toStringAdaptorFactory = $toStringAdaptorFactory;
        $this->methodCallInitialiser = $methodCallInitialiser;
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
     * @param \Exception|string $exception
     *
     * @throws \InvalidArgumentException
     * @return \PHPMockito\Action\FullyActionedMethodCall
     */
    public function thenThrow( $exception ) {
        return $this->methodCallInitialiser->thenThrow( $exception );
    }


    /**
     * @param mixed $value
     *
     * @return FullyActionedMethodCall
     */
    public function thenReturn( $value ) {
        return $this->methodCallInitialiser->thenReturn( $value );
    }
}
