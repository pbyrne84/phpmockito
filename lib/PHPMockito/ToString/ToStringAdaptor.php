<?php

namespace PHPMockito\ToString;

abstract class  ToStringAdaptor {
    const INTERFACE_ToStringAdaptor = __CLASS__;

    /** @var  string */
    protected $prefix;


    /**
     * @param int $indentation
     *
     * @return string
     */
    abstract function toString( $indentation = 0 );


    /**
     * @param string $prefix
     */
    public function setPrefix( $prefix ) {
        $this->prefix = $prefix;
    }


    /**
     * @param string $lines
     * @param int $indentation
     *
     * @return string
     */
    protected function padOutput( $lines, $indentation ) {
        $paddedOutputLines = array();
        $lines             = explode( "\n", $lines );
        foreach ( $lines as $line ) {
            $paddedOutputLines[ ] = str_repeat( ' ', $indentation ) . $line;
        }

        return trim($this->prefix ." ". implode( $paddedOutputLines, "\n" ));
    }


}


 