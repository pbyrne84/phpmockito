<?php
namespace PHPMockito\Action;

use PHPMockito\Expectancy\TestCaseCallVerifier;

function when( $backTrace ) {
    var_dump( $backTrace );

}

class InitialisationCallListenerTest extends \PHPUnit_Framework_TestCase {

    const CLASS_NAME = __CLASS__;

    /**
     * @var TestCaseCallVerifier
     */
    private $initialisationCallListener;


    protected function setUp() {
        $this->initialisationCallListener = new TestCaseCallVerifier( array() );
    }


    public function test_fail() {
        //when( debug_backtrace() );
    }

}
  
