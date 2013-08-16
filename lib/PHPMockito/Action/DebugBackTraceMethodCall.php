<?php

namespace PHPMockito\Action;


use PHPMockito\Mock\MockedClass;

class DebugBackTraceMethodCall extends MethodCall {
    const CLASS_NAME = __CLASS__;

    /** @var array */
    private $debugBacktrace;


    /**
     * @param MockedClass $class
     * @param string      $method
     * @param array       $arguments
     * @param array       $debugBacktrace
     *
     */
    function __construct( MockedClass $class, $method, array $arguments, array $debugBacktrace ) {
        parent::__construct( $class, $method, $arguments );
        $this->debugBacktrace = $debugBacktrace;
    }


    /**
     *
     * /**
     * @return array
     */
    public function getDebugBacktrace() {
        return $this->debugBacktrace;
    }


    /**
     * Creates an in instance of the parent type discarding the debug backtrace
     * @return MethodCall
     */
    public function castToMethodCall() {
        return new MethodCall( $this->getClass(), $this->getMethod(), $this->getArguments() );
    }


}
