<?php

namespace PHPMockito\Expectancy;


use PHPUnit\Framework\TestCase;

class PhpUnitTestCaseInitialisationMatcher implements InitialisationCallMatcher {
    
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
        return $this->parentClassLocator->callWasDoneByTestClass( TestCase::class, $debugBackTrace );
    }
}
