<?php

namespace PHPMockito\ToString;


use PHPMockito\Mock\MockedClass;

class MockedClassToStringAdaptor extends ToStringAdaptor {
    const CLASS_NAME = __CLASS__;

    /** @var MockedClass */
    private $mockedClass;


    /**
     * @param MockedClass $mockedClass
     */
    function __construct( MockedClass $mockedClass ) {
        $this->mockedClass = $mockedClass;
    }


    /**
     * @param int $indentation
     *
     * @return string
     */
    function toString( $indentation = 0 ) {
        return get_class( $this->mockedClass ) . ' (' . $this->mockedClass->getInstanceReference() . ')';
    }
}
 