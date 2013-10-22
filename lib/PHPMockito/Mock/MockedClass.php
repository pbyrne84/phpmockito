<?php

namespace PHPMockito\Mock;


interface MockedClass {
    const INTERFACE_MockedClass = __CLASS__;


    /**
     * @return string
     */
    public function getInstanceReference();


    /**
     * @param string $methodName
     *
     * @return boolean
     */
    public function hasMockedMethod( $methodName );

    /**
     * @param $methodName
     *
     * @return array
     */
    public function getMethodsDefaultParameterMap( $methodName );
}
