<?php

namespace PHPMockito\Caster;


use PHPMockito\Output\ValueOutputExporter;

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
        $valueOutputExporter = new ValueOutputExporter();
        return $valueOutputExporter->convertToString( $this->originalValue );
    }


    public function getOriginalType() {
        return $this->originalType;
    }


    /**
     * @return mixed
     */
    public function getOriginalValue() {
       return $this->originalValue;
    }
}
 