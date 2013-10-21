<?php

namespace PHPMockito\Run;


use PHPMockito\Action\ExpectedMethodCall;
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
     * @return \PHPMockito\Action\MethodCallActionInitialiser
     */
    static public function when(  ) {
        $runtimeState = RuntimeState::getInstance();
        return $runtimeState->newMethodCallActionInitialiser();
    }


    /**
     * @param MockedClass $mockedClass
     * @param int         $expectedCallCount
     *
     * @return \PHPMockito\Verify\Verify
     */
    static public function verify( MockedClass $mockedClass, $expectedCallCount = 1 ) {
        $dependencyFactory = RuntimeState::getInstance()
                ->getDependencyFactory();

        return $dependencyFactory->newVerify( $mockedClass, $expectedCallCount );
    }


    public static function verifyCall( MethodCall $methodCall, $expectedCallCount = 1 ) {
        $dependencyFactory = RuntimeState::getInstance()
                ->getDependencyFactory();

        $dependencyFactory
                ->getMockedMethodCallVerifier()
                ->assertCallCount( $methodCall, $expectedCallCount );
    }


    public static function spy( $className ) {
        $dependencyFactory = RuntimeState::getInstance()
                ->getDependencyFactory();

        $mockFactory = $dependencyFactory->getMockFactory();

        return $mockFactory->spy( $className );
    }


    public static function verifyNoMoreInteractions( $mockedClass ) {
    }
}
