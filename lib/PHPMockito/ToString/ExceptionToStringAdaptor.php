<?php

namespace PHPMockito\ToString;


class ExceptionToStringAdaptor extends ToStringAdaptor {
    const CLASS_NAME = __CLASS__;

    /** @var  \Exception */
    private $exception;


    /**
     * @param \Exception $exception
     */
    function __construct( \Exception $exception ) {
        $this->exception = $exception;
    }


    /**
     * @param int $indentation
     *
     * @return string
     */
    function toString( $indentation = 0 ) {
        return get_class( $this->exception ) .
        "(" . strlen( $this->exception->getMessage() ) . ") '" . $this->exception->getMessage() . '"';
    }
}
 