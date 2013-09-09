<?php

namespace PHPMockito\Expectancy;


class PhpUnitTestCaseInitialisationMatcher implements InitialisationCallMatcher {
    const CLASS_NAME = __CLASS__;


    public function checkIsInitialisationCall( array $debugBackTrace ) {
        $testCallInitialisationDepth = 1;

        if ( count( $debugBackTrace ) < $testCallInitialisationDepth + 1 ) {
            throw new \InvalidArgumentException( 'Backtrace cannot be empty' );
        }

        $parentCallDepth = $testCallInitialisationDepth;
        if ( !isset( $debugBackTrace[ $parentCallDepth ][ 'class' ] ) ) {
            throw new \InvalidArgumentException(
                'class has not been set in backtrace, found ' . print_r( $debugBackTrace[ $parentCallDepth ], true )
            );
        }

        $reflectedFirstIndexClass = new \ReflectionClass( $debugBackTrace[ $parentCallDepth ][ 'class' ] );
        $parent                   = $reflectedFirstIndexClass->getParentClass();
        while ( $parent instanceof \ReflectionClass ) {
            if ( $parent->getName() == 'PHPUnit_Framework_TestCase' ) {
                return true;
            }

            $parent = $parent->getParentClass();
        }

        return false;
    }
}
