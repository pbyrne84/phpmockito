<?php
use PHPMockito\Action\ExpectedMethodCall;
use PHPMockito\Action\MethodCall;
use PHPMockito\Mock\MockedClass;
use PHPMockito\Run\Mockito;

/**
 * @param ExpectedMethodCall|mixed $methodCall
 *
 * @return \PHPMockito\Action\MethodCallActionInitialiser
 */
function when( ExpectedMethodCall $methodCall ) {
    return Mockito::when( $methodCall );
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