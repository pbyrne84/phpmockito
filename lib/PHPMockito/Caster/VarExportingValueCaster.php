<?php

namespace PHPMockito\Caster;


class VarExportingValueCaster implements ValueCaster {
    const CLASS_NAME = __CLASS__;

    private $originalType;

    /** @var mixed */
    private $originalValue;


    /**
     * @param mixed $originalValue
     */
    function __construct( $originalValue ) {
        if ( !is_object( $originalValue ) ) {
            $this->originalType = gettype( $originalValue );
        } else {
            $this->originalType = get_class( $originalValue );
        }

        $this->originalValue = $originalValue;
    }


    public function toComparableString() {
        return var_export( $this->originalValue, true );
    }


    public function getOriginalType() {
        return $this->originalType;
    }
}
 