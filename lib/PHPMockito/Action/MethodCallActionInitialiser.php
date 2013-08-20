<?php

namespace PHPMockito\Action;


use PHPMockito\Expectancy\InitialisationCallRegistrar;

class MethodCallActionInitialiser implements MethodCallAction {
    const CLASS_NAME = __CLASS__;

    /** @var ExpectedMethodCall */
    private $methodCall;

    /** @var InitialisationCallRegistrar */
    private $initialisationCallRegistrar;


    /**
     * @param InitialisationCallRegistrar $initialisationCallRegistrar
     * @param ExpectedMethodCall                  $methodCall
     */
    function __construct( InitialisationCallRegistrar $initialisationCallRegistrar,
                          ExpectedMethodCall $methodCall ) {
        $this->initialisationCallRegistrar = $initialisationCallRegistrar;
        $this->methodCall                  = $methodCall;
    }


    /**
     * @param \Exception|string $exception
     *
     * @throws \InvalidArgumentException
     * @return \PHPMockito\Action\FullyActionedMethodCall
     */
    public function thenThrow( $exception ) {
        if ( !is_string( $exception ) && !$exception instanceof \Exception ) {
            throw new \InvalidArgumentException(
                var_export( $exception, true ) . ' is not an instance of string or exception'
            );
        }

        $fullyActionedMethodCall = new FullyActionedMethodCall(
            $this->methodCall,
            $this->convertExceptionToMethodCallAction( $exception )
        );

        $this->initialisationCallRegistrar->registerMockMethodExpectancy( $fullyActionedMethodCall );

        return $fullyActionedMethodCall;
    }


    /**
     * @param string|\Exception $exception
     *
     * @throws \InvalidArgumentException - if passed a string and the class it refers to cannot be located
     * @return \PHPMockito\Action\MethodCallAction
     */
    private function convertExceptionToMethodCallAction( $exception ) {
        if ( $exception instanceof \Exception ) {
            return new exceptionmethodcallaction( $exception );
        }

        if ( !class_exists( $exception ) ) {
            throw new \Invalidargumentexception( 'exception class "' . $exception . '" not found.' );
        }

        return new exceptionmethodcallaction( new $exception );
    }


    /**
     * @param mixed $value
     *
     * @return FullyActionedMethodCall
     */
    public function thenReturn( $value ) {
        $fullyActionedMethodCall = new FullyActionedMethodCall(
            $this->methodCall,
            new ReturningMethodCallAction( $value )
        );

        $this->initialisationCallRegistrar->registerMockMethodExpectancy( $fullyActionedMethodCall );

        return $fullyActionedMethodCall;
    }
}
