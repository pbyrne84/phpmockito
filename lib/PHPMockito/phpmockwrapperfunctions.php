<?php
use PHPMockito\Action\ExpectedMethodCall;

/**
 * @param ExpectedMethodCall|mixed $methodCall
 *
 * @return \PHPMockito\Action\MethodCallActionInitialiser
 */
function when( ExpectedMethodCall $methodCall ) {
    return \PHPMockito\Run\Mockito::when( $methodCall );
}


function mock( $className ) {
    return \PHPMockito\Run\Mockito::mock( $className );
}