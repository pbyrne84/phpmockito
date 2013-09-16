<?php
use PHPMockito\Action\MethodCall;
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
 * @param MockedClass $mockedClass
 * @param int         $expectedCallCount
 *
 * @return \PHPMockito\Verify\Verify
 */
function verify( MockedClass $mockedClass, $expectedCallCount = 1 ) {
    return Mockito::verify( $mockedClass, $expectedCallCount );
}


function verifyMethodCall( MethodCall $methodCall, $expectedCallCount = 1 ) {
    Mockito::verifyCall( $methodCall, $expectedCallCount );
}


function spy( $className ) {
    return Mockito::spy( $className );
}