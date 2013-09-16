<?php

namespace PHPMockito\Action;


interface MethodCall {
    const INTERFACE_MethodCall = __CLASS__;


    /**
     * @return int
     */
    public function getArgumentCount();


    /**
     * @return array
     */
    public function getArguments();


    /**
     * @param $index
     *
     * @return mixed
     * @throws \OutOfRangeException
     */
    public function getArgument( $index );


    /**
     * @return \PHPMockito\Mock\MockedClass
     */
    public function getClass();


    /**
     * @return string
     */
    public function getMethod();


    /**
     * @return string
     */
    public function convertToString();


}
 