<?php

namespace PHPMockito\Action;


use PHPMockito\ToString\ToStringAdaptorFactory;

abstract class MethodCall implements CallableMethod {
    const CLASS_NAME = __CLASS__;

    /** @var ToStringAdaptorFactory */
    private $toStringAdaptorFactory;

    private $argumentsAsString = null;


    /**
     * @param ToStringAdaptorFactory $toStringAdaptorFactory
     */
    function __construct( ToStringAdaptorFactory $toStringAdaptorFactory ) {
        $this->toStringAdaptorFactory = $toStringAdaptorFactory;
    }


    /**
     * @return string
     */
    public function convertArgumentsToString() {
        if ( $this->argumentsAsString !== null ) {
            return $this->argumentsAsString;
        }

        $arguments = '';
        foreach ( $this->getArguments() as $index => $argument ) {
            $adaptor = $this->toStringAdaptorFactory->createToStringAdaptor( $argument );
            $arguments .= '[' . $index . ']' . $adaptor->toString() . "\n";
        }

        $this->argumentsAsString = sprintf( 'arguments(%s)', $arguments );

        return $this->argumentsAsString;
    }


    /**
     * @return string
     */
    public function getParameterHashCode() {
        return sha1( $this->convertArgumentsToString() );
    }


    /**
     * @return string
     */
    public function getHashedSignature() {
        return $this->getClass()
                ->getInstanceReference() . '->' . $this->getMethod() . '(' . $this->getParameterHashCode() . ')';
    }


    /**
     * @return string
     */
    public function getMethodReference() {
        return $this->getClass()
                ->getInstanceReference() . '->' . $this->getMethod();
    }
} 