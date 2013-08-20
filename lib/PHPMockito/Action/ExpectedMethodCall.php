<?php

namespace PHPMockito\Action;


use PHPMockito\Mock\MockedClass;

class ExpectedMethodCall implements MethodCall {
    const CLASS_NAME = __CLASS__;

    /** @var MockedClass */
    private $class;

    /** @var string */
    private $method;

    /** @var array */
    private $arguments;

    /** @var int */
    private $argumentCount;


    /**
     * @param MockedClass $class
     * @param string      $method
     * @param array       $arguments
     */
    function __construct( MockedClass $class, $method, array $arguments ) {
        $this->class         = $class;
        $this->method        = $method;
        $this->arguments     = $arguments;
        $this->argumentCount = count( $arguments );
    }


    /**
     * @return int
     */
    public function getArgumentCount() {
        return $this->argumentCount;
    }


    /**
     * @return array
     */
    public function getArguments() {
        return $this->arguments;
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

}
 