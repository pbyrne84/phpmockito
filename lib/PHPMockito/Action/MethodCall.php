<?php

namespace PHPMockito\Action;


use PHPMockito\Mock\MockedClass;

class MethodCall {
    const CLASS_NAME = __CLASS__;

    /** @var MockedClass */
    private $class;

    /** @var string */
    private $method;

    /** @var array */
    private $arguments;

    /** @var array */
    private $debugBacktrace;


    /**
     * @param MockedClass $class
     * @param string      $method
     * @param array       $arguments
     * @param array       $debugBacktrace
     */
    function __construct( MockedClass $class, $method, array $arguments, array $debugBacktrace) {
        $this->class     = $class;
        $this->method    = $method;
        $this->arguments = $arguments;
        $this->debugBacktrace = $debugBacktrace;
    }


    /**
     * @return array
     */
    public function getArguments() {
        return $this->arguments;
    }


    /**
     * @return \PHPMockito\Mock\MockedClass
     */
    public function getClass() {
        return $this->class;
    }


    /**
     * @return array
     */
    public function getDebugBacktrace() {
        return $this->debugBacktrace;
    }


    /**
     * @return string
     */
    public function getMethod() {
        return $this->method;
    }


}
