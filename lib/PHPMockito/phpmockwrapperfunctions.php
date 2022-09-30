<?php

use PHPMockito\Action\CallableMethod;
use PHPMockito\Mock\MockedClass;
use PHPMockito\Run\Mockito;

/**
 * @return \PHPMockito\Action\MethodCallActionInitialiser
 */
function when($x) {
    return Mockito::when();
}


/**
 * @template T
 *
 * @param T $className
 *
 * @return T
 */
function mock( $className ) {
    return Mockito::mock( $className );
}


/**
 * @template T
 *
 * @param T   $mockedClass
 * @param int $expectedCallCount
 *
 * @return T
 */
function verify( MockedClass $mockedClass, $expectedCallCount = 1 ) {
    return Mockito::verify( $mockedClass, $expectedCallCount );
}


/**
 * @param CallableMethod $methodCall
 * @param int            $expectedCallCount
 */
function verifyMethodCall( CallableMethod $methodCall, $expectedCallCount = 1 ) {
    Mockito::verifyCall( $methodCall, $expectedCallCount );
}


/**
 * @template T
 *
 * @param T $mockedClass
 *
 * @return T
 */
function verifyNoMoreInteractions( $mockedClass ) {
    Mockito::verifyNoMoreInteractions( $mockedClass );
}


/**
 * @template T
 *
 * @param T $className
 *
 * @return T
 */
function spy( $className ) {
    return Mockito::spy( $className );
}

