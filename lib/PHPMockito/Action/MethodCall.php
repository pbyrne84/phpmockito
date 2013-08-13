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


    /**
     * @param MockedClass $class
     * @param string      $method
     * @param array       $arguments
     */
    function __construct( MockedClass $class, $method, array $arguments ) {
        $this->class     = $class;
        $this->method    = $method;
        $this->arguments = $arguments;
    }


}
