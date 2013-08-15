<?php

namespace PHPMockito\Action;


use PHPMockito\Expectancy\InitalisationCallRegistrar;

class MethodCallActionInitialiser {
    const CLASS_NAME = __CLASS__;

    /** @var MethodCall */
    private $methodCall;

    /** @var InitalisationCallRegistrar */
    private $initalisationCallRegistrar;


    /**
     * @param InitalisationCallRegistrar $initalisationCallRegistrar
     * @param MethodCall                 $methodCall
     */
    function __construct( InitalisationCallRegistrar $initalisationCallRegistrar,
                          MethodCall $methodCall ) {
        $this->initalisationCallRegistrar = $initalisationCallRegistrar;
        $this->methodCall                 = $methodCall;
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

        $this->initalisationCallRegistrar->registerMockMethodExpectancy( $fullyActionedMethodCall );

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
            return new ExceptionMethodCallAction( $exception );
        }

        if ( !class_exists( $exception ) ) {
            throw new \InvalidArgumentException( 'exception class "' . $exception . '" not found.' );
        }

        return new ExceptionMethodCallAction( new $exception );
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

        $this->initalisationCallRegistrar->registerMockMethodExpectancy( $fullyActionedMethodCall );

        return $fullyActionedMethodCall;
    }
}
