<?php

namespace PHPMockito\ToString;


class ScalarToStringAdaptor extends ToStringAdaptor {
    const CLASS_NAME = __CLASS__;

    private $value;


    /**
     * @param mixed $value
     */
    function __construct( $value ) {
        $this->value = $value;
    }


    function toString( $indentation = 0 ) {
        return $this->padOutput(
            gettype( $this->value ) . '(' . strlen( $this->value ) . ') ' . var_export( $this->value, true ),
            $indentation
        );
    }
}
 