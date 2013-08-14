<?php

namespace PHPMockito\Action;


use PHPMockito\Mock\MockedMethod;

class ReturnValueInitialiser {
    const CLASS_NAME = __CLASS__;

    /** @var \PHPMockito\Mock\MockedMethod */
    private $mockedMethod;


    /**
     * @param MockedMethod $mockedMethod
     */
    function __construct( MockedMethod $mockedMethod ) {
        $this->mockedMethod = $mockedMethod;
    }


    /**
     * @param string|\Exception $exception
     */
    public function thenThrow( $exception ) {

    }


    public function thenReturn( $value ) {

    }


}
