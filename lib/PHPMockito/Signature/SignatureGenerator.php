<?php

namespace PHPMockito\Signature;


use PHPMockito\Action\MethodCall;
use PHPMockito\ToString\ToStringAdaptorFactory;

class SignatureGenerator {
    const CLASS_NAME = __CLASS__;

    /** @var \PHPMockito\ToString\ToStringAdaptorFactory */
    private $toStringAdaptorFactory;


    /**
     * @param ToStringAdaptorFactory $toStringAdaptorFactory
     */
    function __construct( ToStringAdaptorFactory $toStringAdaptorFactory ) {
        $this->toStringAdaptorFactory = $toStringAdaptorFactory;
    }


    public function generateMessage( MethodCall $methodCall ) {
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
     * @param MethodCall       $methodCall
     * @param int              $index
     *
     * @return string
     */
    private function getArgumentName( \ReflectionClass $reflectionClass, MethodCall $methodCall, $index ) {
        $reflectionMethod = $reflectionClass->getMethod( $methodCall->getMethod() );
        $reflectionParameters = $reflectionMethod->getParameters();
        return $reflectionParameters[ $index ]->getName();
    }

}
 