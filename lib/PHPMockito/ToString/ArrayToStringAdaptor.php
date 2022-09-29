<?php

namespace PHPMockito\ToString;


class ArrayToStringAdaptor extends  ToStringAdaptor{
    
    /** @var ToStringAdaptorFactory */
    private $toStringAdaptorFactory;

    /** @var array */
    private $array;


    /**
     * @param ToStringAdaptorFactory $toStringAdaptorFactory
     * @param array                  $array
     */
    function __construct( ToStringAdaptorFactory $toStringAdaptorFactory, array $array ) {
        $this->toStringAdaptorFactory = $toStringAdaptorFactory;
        $this->array           = $array;
        $this->setPrefix( 'array' );
    }


    /**
     * @param int $indentation
     *
     * @return string
     */
    function toString( $indentation = 0 ) {
        $string =  ' ('. "\n";
        foreach ( $this->array as $key => $value ) {
            $key = preg_replace( '~\0(.*)\0~', '', $key );
            $toStringAdaptor = $this->toStringAdaptorFactory->createToStringAdaptor( $value );
            $string .= '    [' . $key . '] : ' . $toStringAdaptor->toString( $indentation + 4 ). "\n";
        }

        return $this->padOutput( $string . ")", $indentation );
    }

}
 