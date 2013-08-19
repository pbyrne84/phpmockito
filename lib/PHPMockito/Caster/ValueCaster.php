<?php

namespace PHPMockito\Caster;


interface ValueCaster {
    const INTERFACE_ValueCaster = __CLASS__;


    /**
     * @return string
     */
    public function getOriginalType();


    /**
     * @return string
     */
    public function toComparableString();
}
 