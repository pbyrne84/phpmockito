<?php

namespace PHPMockito\ToString;


use PHPMockito\Mock\MockedClass;

class ToStringAdaptorFactory {
    
    /** @var array */
    private $typeMappings = array();


    function __construct() {
        $this->typeMappings = array(
                MockedClass::class => MockedClassToStringAdaptor::class,
                '\DomDocument'     => DomDocumentToStringAdaptor::class,
                '\SplFileInfo'     => SplFileInfoToStringAdaptor::class,
                '\Exception'       => ExceptionToStringAdaptor::class,
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
 