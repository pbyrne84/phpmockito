<?php
namespace PHPMockito\EndToEnd;

use PHPMockito\Run\Mockito;

class MockitoBasicEndToEndTest extends \PHPUnit_Framework_TestCase {
    const CLASS_NAME = __CLASS__;


    public function test_mock(){
        $DOMDocument = Mockito::mock( '\DomDocument' );
        Mockito::when( $DOMDocument->cloneNode() );

    }


}
 