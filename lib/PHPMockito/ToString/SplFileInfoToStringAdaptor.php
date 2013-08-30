<?php

namespace PHPMockito\ToString;


class SplFileInfoToStringAdaptor extends ToStringAdaptor {
    const CLASS_NAME = __CLASS__;

    /** @var \SplFileInfo */
    private $splFileInfo;


    function __construct( \SplFileInfo $splFileInfo ) {
        $this->splFileInfo = $splFileInfo;
    }


    function toString( $indentation = 0 ) {
        $pathname = $this->splFileInfo->getPathname();

        return get_class( $this->splFileInfo ) . '(' . strlen( $pathname ) . ") '" . $pathname . "'";
    }
}
 