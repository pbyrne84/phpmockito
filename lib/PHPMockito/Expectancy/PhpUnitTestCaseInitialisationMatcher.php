<?php

namespace PHPMockito\Expectancy;


class PhpUnitTestCaseInitialisationMatcher implements InitialisationCallMatcher {
    const CLASS_NAME = __CLASS__;


    public function checkIsInitialisationCall( array $debugBackTrace ) {
        if ( count( $debugBackTrace ) == 0 ) {
            throw new \InvalidArgumentException( 'Backtrace cannot be empty' );
        }

        if ( !isset( $debugBackTrace[ 0 ][ 'class' ] ) ) {
            throw new \InvalidArgumentException(
                'class has not been set in backtrace, found ' . print_r( $debugBackTrace[ 0 ], true )
            );
        }

        $reflectedFirstIndexClass = new \ReflectionClass( $debugBackTrace[ 0 ]['class'] );
        $parent                   = $reflectedFirstIndexClass->getParentClass();
        while ( $parent instanceof \ReflectionClass ) {
            if ( $parent->getName() == 'PHPUnit_Framework_TestCase' ) {
                return true;
            }
        }

        return false;
    }
}
