<?php

namespace PHPMockito\Action;


use PHPMockito\Mock\MockedClass;
use PHPMockito\ToString\ToStringAdaptorFactory;

class DebugBackTraceMethodCall extends ExpectedMethodCall {
    
    /** @var array */
    private $debugBacktrace;


    /**
     * @param ToStringAdaptorFactory $toStringAdaptorFactory
     * @param MockedClass            $class
     * @param string                 $method
     * @param array                  $arguments
     * @param array                  $debugBacktrace
     */
    function __construct( ToStringAdaptorFactory $toStringAdaptorFactory,
                          MockedClass $class,
                          $method,
                          array $arguments,
                          array $debugBacktrace ) {
        parent::__construct( $toStringAdaptorFactory, $class, $method, $arguments );
        $this->debugBacktrace = $this->cleanDebugBackTrace( $debugBacktrace );
    }


    private function cleanDebugBackTrace( $debugBacktrace ) {
        foreach ( $debugBacktrace as $row => $valueMap ) {
            unset( $debugBacktrace[ $row ][ 'args' ]);
        }

        return $debugBacktrace;
    }


    /**
     * @return array
     */
    public function getDebugBacktrace() {
        return $this->debugBacktrace;
    }


    /**
     * Creates an in instance of the parent type discarding the debug backtrace
     * @return ExpectedMethodCall
     */
    public function castToMethodCall() {
        return new ExpectedMethodCall(
            $this->getToStringAdaptorFactory(),
            $this->getClass(),
            $this->getMethod(),
            $this->getArguments()
        );
    }
}
