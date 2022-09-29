<?php

namespace PHPMockito\TestClass;


class MagicMethodTestClass {
    

    /**
     * @param $name
     * @param $arguments
     *
     * @return mixed
     * @throws \BadMethodCallException
     */
    function __call( $name, $arguments ) {
        // TODO: Implement __call() method.
        throw new \BadMethodCallException( "Method not implemented" );
    }


    function __set( $name, $value ) {
        // TODO: Implement __set() method.
        throw new \BadMethodCallException( "Method not implemented" );
    }


    function refererence( &$moo ) {

    }


}
 