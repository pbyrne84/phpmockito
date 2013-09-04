<?php

namespace PHPMockito\Expectancy;


interface InitialisationCallListenerFactory {
    const INTERFACE_InitialisationCallListenerFactory = __CLASS__;


    /**
     * @return TestCaseCallVerifier
     */
    public function createTestCaseCallVerifier();


}
