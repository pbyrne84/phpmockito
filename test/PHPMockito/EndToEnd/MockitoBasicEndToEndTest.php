<?php
namespace PHPMockito\EndToEnd;

use PHPMockito\Run\Mockito;
use PHPMockito\TestClass\UsageTestClass;

class MockitoBasicEndToEndTest extends \PHPUnit_Framework_TestCase {
    const CLASS_NAME = __CLASS__;


    public function test_mock_returnValue() {
        $DOMDocument = Mockito::mock( '\DomDocument' );
        Mockito::when( $DOMDocument->cloneNode( true ) )
                ->thenReturn( 'MOO' );

        Mockito::when( $DOMDocument->cloneNode() )
                ->thenReturn( 'Baaa' );

        $usageTestClass = new UsageTestClass( $DOMDocument );
        $this->assertEquals( 'MOO', $usageTestClass->testTrue() );
        $this->assertEquals( 'Baaa', $usageTestClass->testFalse() );
    }


    /**
     * @expectedException \InvalidArgumentException
     */
    public function test_mock_exception() {
        $DOMDocument = Mockito::mock( '\DomDocument' );
        Mockito::when( $DOMDocument->cloneNode( true ) )
                ->thenThrow( new \InvalidArgumentException() );

        $usageTestClass = new UsageTestClass( $DOMDocument );
        $DOMNode        = $usageTestClass->testTrue();
    }


}
 