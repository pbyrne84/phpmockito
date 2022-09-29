<?php

namespace PHPMockito\Action;


class ReturningMethodCallAction implements MethodCallAction {
    
    /** @var mixed */
    private $returnValue;


    /**
     * @param mixed $returnValue
     */
    function __construct( $returnValue ) {
        $this->returnValue = $returnValue;
    }


    /**
     * @return mixed
     */
    public function getReturnValue() {
        return $this->returnValue;
    }


    public function thenThrow( $exception ) {
        // TODO: Implement thenThrow() method.
        throw new \BadMethodCallException( "Method not implemented" );
    }


    public function thenReturn( $value ) {
        // TODO: Implement thenReturn() method.
        throw new \BadMethodCallException( "Method not implemented" );
    }
}
