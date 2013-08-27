<?php
namespace PHPMockito\Action;

use PHPMockito\Expectancy\InitialisationCallRegistrar;

class ReturnValueInitialiserTest extends \PHPUnit_Framework_TestCase {

    const CLASS_NAME = __CLASS__;


    protected function setUp() {
    }


    public function test_thenThrow_passedExceptionInstanceIsSet() {
        /** @var $initialisationCallRegistrar InitialisationCallRegistrar */
        $initialisationCallRegistrar = mock( InitialisationCallRegistrar::INTERFACE_InitalisationCallRegistrar );
        $returnValueInitialiser = new MethodCallActionInitialiser(
            $initialisationCallRegistrar,
            mock( ExpectedMethodCall::CLASS_NAME)
        );


        $this->assertTrue( false, 'Add tests' );
    }

}
  
