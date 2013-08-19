<?php

namespace PHPMockito\Caster;

use PHPMockito\Mock\MockedClass;

class ValueCasterFactory {
    const CLASS_NAME = __CLASS__;

    private $customCasterCasterMap = array();


    function __construct() {
        $this->customCasterCasterMap = array(
            '\DomDocument' => function ( $value ) {
                return new DomDocumentValueCaster( $value );
            },
            '\SplFileInfo' => function ( $value ) {
                return new SplFileInfoValueCaster( $value );
            },
        );
    }


    /**
     * Php has problems with casting certain class for comparison use, for example SplFile info cannot be serialised
     * leading to a fatal error, or in other cases false positives such as dom documents. For classes like these a custom
     * method for value attainment needs to be added and made configurable, such as for dom documents making sure the
     * dom documents have the right white space settings before comparison with saveXml.
     *
     * In Java this is not really an issue as equals method is usually auto generated to handle every equals case
     * but the verbosity of this is probably not welcomed in PHP land, plus and equals/toString generator would have to
     * be written and gain traction.
     *
     * One problem with this that can happen is complex internal data structure need a caster equals to an
     * equals/toString method to go down deeper.
     *
     * @param mixed $value
     *
     * @return ValueCaster
     */
    public function castValueToComparableType( $value ) {
        if ( !is_object( $value ) ) {
            return new VarExportingValueCaster( $value );
        }

        if( $value instanceof MockedClass ){
            return new MockValueCaster( $value );
        }

        foreach ( $this->customCasterCasterMap as $className => $factoryClosure ) {
            if ( $value instanceof $className || is_subclass_of( $value, $className ) ) {
                return $factoryClosure( $value );
            }
        }

        return new VarExportingValueCaster( $value );
    }
}
 