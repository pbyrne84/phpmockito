<?php

namespace PHPMockito\Signature;


use PHPMockito\Action\CallableMethod;
use PHPMockito\ToString\ToStringAdaptorFactory;

class SignatureGenerator {
    
    /** @var \PHPMockito\ToString\ToStringAdaptorFactory */
    private $toStringAdaptorFactory;


    /**
     * @param ToStringAdaptorFactory $toStringAdaptorFactory
     */
    function __construct( ToStringAdaptorFactory $toStringAdaptorFactory ) {
        $this->toStringAdaptorFactory = $toStringAdaptorFactory;
    }


    public function generateMessage( CallableMethod $methodCall ) {
        $reflectionClass = new \ReflectionClass( get_class( $methodCall->getClass() ) );
        $methodSignature = get_class( $methodCall->getClass() ) . '->' . $methodCall->getMethod() . "(" . PHP_EOL;
        foreach ( $methodCall->getArguments() as $index => $argument ) {
            $toStringAdaptor = $this->toStringAdaptorFactory->createToStringAdaptor( $argument );
            $methodSignature .=
                    '  $' . $this->getArgumentName( $reflectionClass, $methodCall, $index ) .
                    '(arg[' . $index . ']) = ' . $toStringAdaptor->toString() . ',' . PHP_EOL ;
        }

        return rtrim( $methodSignature, "\n\r,") . PHP_EOL . ")";
    }


    /**
     * @param \ReflectionClass $reflectionClass
     * @param CallableMethod       $methodCall
     * @param int              $index
     *
     * @return string
     */
    private function getArgumentName( \ReflectionClass $reflectionClass, CallableMethod $methodCall, $index ) {
        $reflectionMethod = $reflectionClass->getMethod( $methodCall->getMethod() );
        $reflectionParameters = $reflectionMethod->getParameters();
        $declaredParameterCount = count( $reflectionParameters );
        if ( $index >= $declaredParameterCount ) {
            $baseNameOfVariadic = $reflectionParameters[ count( $reflectionParameters ) - 1 ]->getName();

            return $baseNameOfVariadic . '...' . ( $index - $declaredParameterCount + 1 ) ;
        }

        return $reflectionParameters[ $index ]->getName();
    }

}
