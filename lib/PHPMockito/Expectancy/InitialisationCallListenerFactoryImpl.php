<?php

namespace PHPMockito\Expectancy;


class InitialisationCallListenerFactoryImpl implements InitialisationCallListenerFactory {
    
    private $ignorableNonProductionTestClassSet = array();


    /**
     * @return TestCaseCallVerifier
     */
    public function createTestCaseCallVerifier() {
        return new TestCaseCallVerifier(
                array(
                        new PhpUnitTestCaseInitialisationMatcher(),
                        new CustomInitialisationCallMatcher( array_values( $this->ignorableNonProductionTestClassSet ) )
                )
        );
    }


    /**
     * @param string $fullyQualifiedClassName
     */
    public function addIgnorableNonProductionTestClass( $fullyQualifiedClassName ) {
        $this->ignorableNonProductionTestClassSet[ $fullyQualifiedClassName ] = $fullyQualifiedClassName;
    }

}