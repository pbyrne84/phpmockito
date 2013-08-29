<?php

namespace PHPMockito\ToString;


class GenericObjectToStringAdaptor extends ToStringAdaptor {
    const CLASS_NAME = __CLASS__;

    /** @var ToStringAdaptorFactory */
    private $toStringAdaptorFactory;

    /** @var object */
    private $value;


    /**
     * @param ToStringAdaptorFactory $toStringAdaptorFactory
     * @param object                 $value
     */
    function __construct( ToStringAdaptorFactory $toStringAdaptorFactory, $value ) {

        $this->toStringAdaptorFactory = $toStringAdaptorFactory;
        $this->value                  = $value;
    }


    function toString( $indentation = 0 ) {
        $toStringAdaptor = $this->toStringAdaptorFactory->createToStringAdaptor( (array)$this->value );
        $toStringAdaptor->setPrefix( get_class( $this->value ) );

        return $this->padOutput( $toStringAdaptor->toString( $indentation + 4 ), $indentation );
    }
}
 