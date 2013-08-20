<?php
namespace PHPMockito\EndToEnd;

use PHPMockito\TestClass\UsageTestClass;

class MockitoBasicEndToEndTest extends \PHPUnit_Framework_TestCase {
    const CLASS_NAME = __CLASS__;


    public function test_mock_returnValue() {
        $DOMDocument = mock( '\DomDocument' );
        $methodCall1 = when( $DOMDocument->cloneNode( true ) )
                ->thenReturn( 'MOO' );

        $methodCall2 = when( $DOMDocument->cloneNode() )
                ->thenReturn( 'Baaa' );

        $usageTestClass = new UsageTestClass( $DOMDocument );
        $this->assertEquals( 'MOO', $usageTestClass->testTrue() );
        $this->assertEquals( 'Baaa', $usageTestClass->testFalse() );

        verify( $DOMDocument, 1 )->cloneNode( true );
        verify( $DOMDocument, 1 )->cloneNode( );

        verifyMethodCall( $methodCall1 );
        verifyMethodCall( $methodCall2 );
    }


    /**
     * @expectedException \InvalidArgumentException
     */
    public function test_mock_exception() {
        $DOMDocument = mock( '\DomDocument' );
        when( $DOMDocument->cloneNode( true ) )
                ->thenThrow( new \InvalidArgumentException() );

        $usageTestClass = new UsageTestClass( $DOMDocument );
        $DOMNode        = $usageTestClass->testTrue();
    }


}
 