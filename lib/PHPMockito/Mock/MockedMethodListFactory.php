<?php

namespace PHPMockito\Mock;

use ReflectionMethod;

class MockedMethodListFactory {
    

    /**
     * @param \ReflectionClass $reflectionClass
     *
     * @return MockedMethod[]
     */
    public function createProtectedAndPublicListFromReflectionClass( \ReflectionClass $reflectionClass ) {
        $mockedMethodList = array();
        foreach ( $reflectionClass->getMethods( ReflectionMethod::IS_PROTECTED ) as $reflectionMethod ) {
            if ( !$reflectionMethod->isConstructor() && !$reflectionMethod->isFinal() ) {
                $mockedMethodList[ ] = new MockedMethod( $reflectionMethod );
            }
        }

        return array_merge( $mockedMethodList, $this->createPublicListFromReflectionClass( $reflectionClass ) );
    }


    /**
     * @param \ReflectionClass $reflectionClass
     *
     * @return MockedMethod[]
     */
    public function createPublicListFromReflectionClass( \ReflectionClass $reflectionClass ) {
        $mockedMethodList = array();
        foreach ( $reflectionClass->getMethods( ReflectionMethod::IS_PUBLIC ) as $reflectionMethod ) {
            if ( !$reflectionMethod->isConstructor() && !$reflectionMethod->isFinal() && !$reflectionMethod->isStatic(
                    ) && $reflectionMethod->getName() != '__destruct'
            ) {
                $mockedMethodList[ ] = new MockedMethod( $reflectionMethod );
            }
        }

        return $mockedMethodList;
    }


}
 