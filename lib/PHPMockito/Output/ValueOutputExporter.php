<?php

namespace PHPMockito\Output;

use PHPMockito\Mock\MockedClass;

class ValueOutputExporter {
    

    /**
     * @param mixed $value
     * @param int   $depth
     *
     * @return string
     */
    public function convertToString( $value, $depth = 0 ) {
        if ( is_object( $value ) ) {
            return $this->convertObjectToString( $value, $depth );
        } elseif ( is_array( $value ) ) {
            return $this->convertArrayToString( $value, $depth );
        }

        return $this->padOutput( $value, $depth );
    }


    /**
     * @param object $value
     * @param int    $depth
     *
     * @return string
     */
    private function convertObjectToString( $value, $depth ) {
        $baseString = get_class( $value ) . " ";

        if ( $value instanceof \Exception ) {
            $exceptionAsString = $baseString;

            return PHP_EOL . $this->padOutput( $exceptionAsString, $depth ) . PHP_EOL;
        } elseif ( $value instanceof MockedClass ) {
            return PHP_EOL . $this->padOutput( $baseString . $value->getInstanceReference(), $depth ) . PHP_EOL;
        } elseif ( $value instanceof \DOMDocument ) {
            return PHP_EOL . $this->padOutput( $baseString . 'xmlString(' . strlen( $value->saveXML() ) . ')"' . $value->saveXML() . '"', $depth ) . PHP_EOL;
        }

        $output = PHP_EOL . $this->padOutput( $baseString . $this->convertArrayToString( (array)$value, $depth + 4 ), $depth );

        return $output . PHP_EOL;
    }


    /**
     * @param string $text
     * @param int    $depth
     *
     * @return string
     */
    private function padOutput( $text, $depth ) {
        $padding = str_pad( '', $depth, ' ', STR_PAD_LEFT );

        return $padding . $text;
    }


    /**
     * @param array $array
     * @param int   $depth
     *
     * @return string
     */
    private function convertArrayToString( array $array, $depth ) {
        $string = '';
        foreach ( $array as $keyName => $value ) {
            $keyName = $this->clearKeyName( $keyName );
            $string .= PHP_EOL . $this->padOutput( trim( $keyName ) . ' => ' . trim( $this->convertToString( $value, $depth + 4 ) ), $depth );
        }

        return $string;
    }


    /**
     * @param $keyName
     *
     * @return string
     */
    private function clearKeyName( $keyName ) {
        return preg_replace( '~\0(.*)\0~', '', $keyName );
    }
}
 