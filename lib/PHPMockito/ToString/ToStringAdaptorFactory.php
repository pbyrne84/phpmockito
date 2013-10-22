<?php

namespace PHPMockito\ToString;


use PHPMockito\Mock\MockedClass;

class ToStringAdaptorFactory {
    const CLASS_NAME = __CLASS__;

    /** @var array */
    private $typeMappings = array();


    function __construct() {
        $this->typeMappings = array(
            MockedClass::INTERFACE_MockedClass => MockedClassToStringAdaptor::CLASS_NAME,
            '\DomDocument'                     => DomDocumentToStringAdaptor::CLASS_NAME,
            '\SplFileInfo'                     => SplFileInfoToStringAdaptor::CLASS_NAME,
            '\Exception'                       => ExceptionToStringAdaptor::CLASS_NAME,
        );
    }


    /**
     * @param $value
     *
     * @return ToStringAdaptor
     */
    public function createToStringAdaptor( $value ) {
        if ( is_object( $value ) ) {
            foreach ( $this->typeMappings as $typeName => $adaptor ) {
                if ( $value instanceof $typeName ) {
                    /** @var $adaptor ToStringAdaptor */
                    $adaptor = new $adaptor( $value );

                    return $adaptor;
                }
            }

            $genericObjectToStringAdaptor = new GenericObjectToStringAdaptor( $this, $value );

            return $genericObjectToStringAdaptor;
        }

        if ( is_array( $value ) ) {
            $arrayToStringAdaptor = new ArrayToStringAdaptor( $this, $value );

            return $arrayToStringAdaptor;
        }

        return new ScalarToStringAdaptor( $value );
    }
}
 