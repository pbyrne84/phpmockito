<?php

namespace PHPMockito\Expectancy;


class PhpUnitTestCaseInitialisationMatcher implements InitialisationCallMatcher {
    const CLASS_NAME = __CLASS__;

    /** @var TestClassCallBacktraceDetector   */
    private $parentClassLocator;


    function __construct() {
        $this->parentClassLocator = new TestClassCallBacktraceDetector();
    }


    /**
     * @param array $debugBackTrace
     *
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function checkIsInitialisationCall( array $debugBackTrace ) {
        return $this->parentClassLocator->callWasDoneByTestClass( 'PHPUnit_Framework_TestCase', $debugBackTrace );
    }
}
