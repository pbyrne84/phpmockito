<?php

namespace PHPMockito\Expectancy;


class CustomInitialisationCallMatcher implements InitialisationCallMatcher {
    const CLASS_NAME = __CLASS__;

    private $testClassCallBacktraceDetector;

    /** @var array */
    private $fullQualifiedClassNameList;


    /**
     * @param array $fullQualifiedClassNameList
     */
    function __construct( array $fullQualifiedClassNameList ) {
        $this->fullQualifiedClassNameList     = $fullQualifiedClassNameList;
        $this->testClassCallBacktraceDetector = new TestClassCallBacktraceDetector();
    }


    public function checkIsInitialisationCall( array $debugBackTrace ) {
        foreach ( $this->fullQualifiedClassNameList as $fullyQualifiedName ) {
            if ( $this->testClassCallBacktraceDetector->callWasDoneByTestClass( $fullyQualifiedName, $debugBackTrace ) ) {
                return true;
            }
        }

        return false;
    }
}
 