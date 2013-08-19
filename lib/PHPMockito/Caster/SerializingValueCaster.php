<?php

namespace PHPMockito\Caster;


class SerializingValueCaster implements ValueCaster {
    const CLASS_NAME = __CLASS__;

    private $originalType;

    /** @var mixed */
    private $originalValue;


    /**
     * @param mixed $originalValue
     */
    function __construct( $originalValue ) {
        $this->originalType  = gettype( $originalValue );
        $this->originalValue = $originalValue;
    }


    public function toPrimitive() {
        return serialize( $this->originalValue );
    }


    public function getOriginalType() {
        return $this->originalType;
    }
}
 