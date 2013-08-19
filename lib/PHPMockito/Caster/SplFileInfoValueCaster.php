<?php

namespace PHPMockito\Caster;


class SplFileInfoValueCaster implements ValueCaster{
    const CLASS_NAME = __CLASS__;

    /** @var \SplFileInfo */
    private $splFileInfo;


    /**
     * @param \SplFileInfo $splFileInfo
     */
    function __construct( \SplFileInfo $splFileInfo ) {
        $this->splFileInfo = $splFileInfo;
    }


    /**
     * @return string
     */
    public function getOriginalType() {
        return get_class( $this->splFileInfo );
    }


    /**
     * @return string
     */
    public function toComparableString() {
        return $this->splFileInfo->getPathname();
    }
}
 