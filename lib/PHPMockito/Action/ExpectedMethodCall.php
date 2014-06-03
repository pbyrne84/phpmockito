<?php

namespace PHPMockito\Action;


use PHPMockito\Mock\MockedClass;
use PHPMockito\ToString\ToStringAdaptorFactory;

class ExpectedMethodCall extends MethodCall {
    const CLASS_NAME = __CLASS__;

    /** @var MockedClass */
    private $class;

    /** @var string */
    private $method;

    /** @var array */
    private $arguments;

    /** @var int */
    private $argumentCount;

    /** @var ToStringAdaptorFactory */
    private $toStringAdaptorFactory;


    /**
     * @param ToStringAdaptorFactory $toStringAdaptorFactory
     * @param MockedClass            $class
     * @param string                 $method
     * @param array                  $arguments
     */
    function __construct( ToStringAdaptorFactory $toStringAdaptorFactory,
                          MockedClass $class,
                          $method,
                          array $arguments ) {

        parent::__construct( $toStringAdaptorFactory );

        $clonedArguments = array();
        foreach ( $arguments as $argument ) {
            $clonedArguments[ ] = $this->tryCloningArgument( $argument );
        }

        $this->class                  = $class;
        $this->method                 = $method;
        $this->arguments              = $clonedArguments;
        $this->argumentCount          = count( $arguments );
        $this->toStringAdaptorFactory = $toStringAdaptorFactory;
    }


    /**
     * @param mixed $argument
     *
     * @return mixed
     */
    private function tryCloningArgument( $argument ) {
        $nonCloneableClassList = array( '\\Exception' );
        if ( !is_object( $argument ) ) {
            return $argument;
        }

        foreach ( $nonCloneableClassList as $nonCloneableClass ) {
            if ( $argument instanceof $nonCloneableClass ) {
                return $argument;
            }
        }

        return clone $argument;
    }


    /**
     * @return int
     */
    public function getArgumentCount() {
        return $this->argumentCount;
    }


    /**
     * @param $index
     *
     * @return mixed
     * @throws \OutOfRangeException
     */
    public function getArgument( $index ) {
        if ( !array_key_exists( $index, $this->arguments ) ) {
            throw new \OutOfRangeException(
                    $index . ' is not set, the only argument available are ' . array_keys( $this->arguments )
            );
        }

        return $this->arguments[ $index ];
    }


    /**
     * @return \PHPMockito\Mock\MockedClass
     */
    public function getClass() {
        return $this->class;
    }


    /**
     * @return string
     */
    public function getMethod() {
        return $this->method;
    }


    /**
     * @return array
     */
    public function getArguments() {
        return $this->arguments;
    }


    /**
     * @return ToStringAdaptorFactory
     */
    protected function getToStringAdaptorFactory() {
        return $this->toStringAdaptorFactory;
    }
}
 