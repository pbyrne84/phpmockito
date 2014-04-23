<?php
use PHPMockito\Action\CallableMethod;
use PHPMockito\Mock\MockedClass;
use PHPMockito\Run\Mockito;

/**
 * @return \PHPMockito\Action\MethodCallActionInitialiser
 */
function when() {
    return Mockito::when();
}


/**
 * @param string $className
 *
 * @return mixed
 */
function mock( $className ) {
    return Mockito::mock( $className );
}


/**
 * @param MockedClass|mixed $mockedClass
 * @param int               $expectedCallCount
 *
 * @return \PHPMockito\Verify\Verify
 */
function verify( MockedClass $mockedClass, $expectedCallCount = 1 ) {
    return Mockito::verify( $mockedClass, $expectedCallCount );
}


/**
 * @param CallableMethod $methodCall
 * @param int        $expectedCallCount
 */
function verifyMethodCall( CallableMethod $methodCall, $expectedCallCount = 1 ) {
    Mockito::verifyCall( $methodCall, $expectedCallCount );
}

/**
 * @param MockedClass|mixed $mockedClass
 */
function verifyNoMoreInteractions( $mockedClass ) {
    Mockito::verifyNoMoreInteractions( $mockedClass );
}


/**
 * @param string $className
 *
 * @return mixed
 */
function spy( $className ) {
    return Mockito::spy( $className );
}