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
        $methodSignature = get_class( $methodCall->getClass() ) . '->' . $methodCall->getMethod() . "()" . PHP_EOL;
        foreach ( $methodCall->getArguments() as $index => $argument ) {
            $toStringAdaptor = $this->toStringAdaptorFactory->createToStringAdaptor( $argument );
            $methodSignature .=
                    '   arg[' . $index . '] = ' . $toStringAdaptor->toString( ) . PHP_EOL;
        }

        return $methodSignature;
    }

}
 