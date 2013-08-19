<?php

namespace PHPMockito\Caster;


use PHPMockito\Mock\MockedClass;

class MockValueCaster implements ValueCaster{
    const CLASS_NAME = __CLASS__;

    /** @var MockedClass */
    private $mockedClass;


    /**
     * @param MockedClass $mockedClass
     */
    function __construct( MockedClass $mockedClass) {
        $this->mockedClass = $mockedClass;
    }


    /**
     * @return string
     */
    public function getOriginalType() {
        return get_class( $this->mockedClass );
    }


    /**
     * @return string
     */
    public function toPrimitive() {
        return $this->mockedClass->getInstanceReference();
    }
}
 