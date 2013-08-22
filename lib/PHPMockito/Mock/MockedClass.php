<?php

namespace PHPMockito\Mock;


interface MockedClass {
    const INTERFACE_MockedClass = __CLASS__;


    /**
     * @return string
     */
    public function getInstanceReference();


    /**
     * @param $methodName
     *
     * @return array
     */
    public function getMethodsDefaultParameterMap( $methodName );
}
