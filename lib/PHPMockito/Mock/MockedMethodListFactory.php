<?php

namespace PHPMockito\Mock;

use ReflectionMethod;

class MockedMethodListFactory {
    const CLASS_NAME = __CLASS__;


    /**
     * @param \ReflectionClass $reflectionClass
     *
     * @return MockedMethod[]
     */
    public function createPublicListFromReflectionClass( \ReflectionClass $reflectionClass ) {
        $mockedMethodList = array();
        foreach ( $reflectionClass->getMethods( ReflectionMethod::IS_PUBLIC ) as $reflectionMethod ) {
            if ( !$reflectionMethod->isConstructor() && !$reflectionMethod->isFinal() ) {
                $mockedMethodList[ ] = new MockedMethod( $reflectionMethod );
            }
        }

        return $mockedMethodList;
    }
}
 