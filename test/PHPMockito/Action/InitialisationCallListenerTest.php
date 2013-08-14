<?php
namespace PHPMockito\Action;

use PHPMockito\Expectancy\InitialisationCallListener;

function when( $backTrace ){
    var_dump( $backTrace );

}

class InitialisationCallListenerTest extends  \PHPUnit_Framework_TestCase{

    const CLASS_NAME = __CLASS__;

    /**
     * @var InitialisationCallListener
     */
    private $initialisationCallListener;


    protected function setUp() {
        $this->initialisationCallListener = new InitialisationCallListener( array() );
    }

    public function test_fail(){
        when( debug_backtrace() );
    }

}
  
