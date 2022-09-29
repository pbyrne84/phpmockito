<?php

namespace PHPMockito\Expectancy;


class TestClassCallBacktraceDetector {
    

    public function callWasDoneByTestClass( $testClass, array $debugBackTrace ) {
        $testCallInitialisationDepth = 1;

        return $this->runCallAnalysis( $testClass, $debugBackTrace, $testCallInitialisationDepth );
    }


    /**
     * @param stirng $testClass
     * @param array  $debugBackTrace
     * @param int    $testCallInitialisationDepth
     *
     * @return bool
     * @throws \InvalidArgumentException
     */
    private function runCallAnalysis( $testClass, array $debugBackTrace, $testCallInitialisationDepth ) {
        if ( count( $debugBackTrace ) < $testCallInitialisationDepth + 1 ) {
            throw new \InvalidArgumentException( 'Backtrace cannot be empty' );
        }

        $parentCallDepth = $testCallInitialisationDepth;
        if ( false !== strpos( $debugBackTrace[ $parentCallDepth ][ 'function' ], '{closure}' ) ) {
            return $this->runCallAnalysis( $testClass, $debugBackTrace, ++$testCallInitialisationDepth );
        }

        if ( !isset( $debugBackTrace[ $parentCallDepth ][ 'class' ] ) ) {
            throw new \InvalidArgumentException(
                'class has not been set in backtrace, found ' . print_r( $debugBackTrace[ $parentCallDepth ], true )
            );
        }

        $reflectedFirstIndexClass = new \ReflectionClass( $debugBackTrace[ $parentCallDepth ][ 'class' ] );
        if ( $reflectedFirstIndexClass->getName() == $testClass ) {
            return true;
        }

        $parent = $reflectedFirstIndexClass->getParentClass();
        while ( $parent instanceof \ReflectionClass ) {
            if ( $parent->getName() == $testClass ) {
                return true;
            }

            $parent = $parent->getParentClass();
        }

        return false;
    }
}
 