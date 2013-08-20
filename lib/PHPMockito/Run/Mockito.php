<?php

namespace PHPMockito\Run;


use PHPMockito\Action\MethodCall;
use PHPMockito\Action\MethodCallActionInitialiser;
use PHPMockito\Mock\MockedClass;

class Mockito {
    const CLASS_NAME = __CLASS__;


    /**
     * @param string $className
     *
     * @return mixed
     */
    static public function mock( $className ) {
        $dependencyFactory = RuntimeState::getInstance()
                ->getDependencyFactory();

        $mockFactory = $dependencyFactory->getMockFactory();

        return $mockFactory->mock( $className );
    }


    /**
     * @param MethodCall|mixed $methodCall
     *
     * @return \PHPMockito\Action\MethodCallActionInitialiser
     */
    static public function when( MethodCall $methodCall ) {
        return new MethodCallActionInitialiser( RuntimeState::getInstance(), $methodCall );
    }


    /**
     * @param MockedClass $mockedClass
     * @param int         $expectedCallCount
     */
    static public function verify( MockedClass $mockedClass, $expectedCallCount = 0 ) {

    }
}
