<?php

namespace PHPMockito\ToString;


class SplFileInfoToStringAdaptor extends ToStringAdaptor {
    
    /** @var \SplFileInfo */
    private $splFileInfo;


    /**
     * @param \SplFileInfo $splFileInfo
     */
    function __construct( \SplFileInfo $splFileInfo ) {
        $this->splFileInfo = $splFileInfo;
    }


    /**
     * @param int $indentation
     *
     * @return string
     */
    function toString( $indentation = 0 ) {
        $pathname = $this->splFileInfo->getPathname();

        return get_class( $this->splFileInfo ) . '(' . strlen( $pathname ) . ") '" . $pathname . "'";
    }
}
 