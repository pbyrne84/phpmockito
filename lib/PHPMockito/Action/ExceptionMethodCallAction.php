<?php

namespace PHPMockito\Action;


class ExceptionMethodCallAction implements MethodCallAction {
    
    /** @var \Exception */
    private $exceptionToBeThrown;


    function __construct( \Exception $exceptionToBeThrown ) {
        $this->exceptionToBeThrown = $exceptionToBeThrown;
    }


    /**
     * @return \Exception
     */
    public function getExceptionToBeThrown() {
        return $this->exceptionToBeThrown;
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
