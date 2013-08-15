<?php
namespace PHPMockito\Action;

class ReturnValueInitialiserTest extends \PHPUnit_Framework_TestCase {

    const CLASS_NAME = __CLASS__;


    protected function setUp() {
    }


    public function test_thenThrow_passedExceptionInstanceIsSet() {
        $returnValueInitialiser = new MethodCallActionInitialiser( new MethodCall() );
        $this->assertTrue( false, 'Add tests' );
    }

}
  
