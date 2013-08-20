<?php
use PHPMockito\Action\MethodCall;

/**
 * @param MethodCall|mixed $methodCall
 *
 * @return \PHPMockito\Action\MethodCallActionInitialiser
 */
function when( MethodCall $methodCall ) {
    return \PHPMockito\Run\Mockito::when( $methodCall );
}


function mock( $className ) {
    return \PHPMockito\Run\Mockito::mock( $className );
}