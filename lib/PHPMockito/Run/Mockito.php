<?php

namespace PHPMockito\Run;


use PHPMockito\Action\ExpectedMethodCall;
use PHPMockito\Action\CallableMethod;
use PHPMockito\Action\MethodCallActionInitialiser;
use PHPMockito\Mock\MockedClass;

class Mockito {
    const CLASS_NAME = __CLASS__;


    /**
     * @template T
     *
     * @param T $className
     *
     * @return T
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
    static public function when() {
        $runtimeState = RuntimeState::getInstance();

        return $runtimeState->createMethodCallActionInitialiser();
    }


    /**
     * @param MockedClass|mixed $mockedClass
     * @param int               $expectedCallCount
     *
     * @return \PHPMockito\Verify\Verify
     */
    static public function verify( MockedClass $mockedClass, $expectedCallCount = 1 ) {
        $dependencyFactory = RuntimeState::getInstance()
                ->getDependencyFactory();

        return $dependencyFactory->createVerify( $mockedClass, $expectedCallCount );
    }


    /**
     * @param CallableMethod $methodCall
     * @param int        $expectedCallCount
     */
    public static function verifyCall( CallableMethod $methodCall, $expectedCallCount = 1 ) {
        $dependencyFactory = RuntimeState::getInstance()
                ->getDependencyFactory();

        $dependencyFactory
                ->getMockedMethodCallVerifier()
                ->assertCallCount( $methodCall, $expectedCallCount, false );
    }


    /**
     * @param string $className
     *
     * @return mixed
     */
    public static function spy( $className ) {
        $dependencyFactory = RuntimeState::getInstance()
                ->getDependencyFactory();

        $mockFactory = $dependencyFactory->getMockFactory();

        return $mockFactory->spy( $className );
    }


    /**
     * @param MockedClass|mixed $mockedClass
     */
    public static function verifyNoMoreInteractions( $mockedClass ) {
        $dependencyFactory = RuntimeState::getInstance()
                ->getDependencyFactory();

        $mockedMethodCallVerifier = $dependencyFactory->getMockedMethodCallVerifier();
        $mockedMethodCallVerifier->verifyNoMoreInteractions( $mockedClass );

    }

}
