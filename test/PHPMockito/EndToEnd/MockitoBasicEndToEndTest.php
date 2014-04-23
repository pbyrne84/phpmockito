<?php
namespace PHPMockito\EndToEnd;

use PHPMockito\TestClass\MagicMethodTestClass;
use PHPMockito\TestClass\UsageTestClass;

class MockitoBasicEndToEndTest extends \PHPUnit_Framework_TestCase {
    const CLASS_NAME = __CLASS__;


    public function test_mock_returnValue() {
        $DOMDocument = mock( '\DomDocument' );
        $methodCall1 =
                when( $DOMDocument->cloneNode( true ) )
                        ->thenReturn( 'MOO' );

        $methodCall2 =
                when( $DOMDocument->cloneNode( null ) )
                        ->thenReturn( 'Baaa' );

        $methodCall3ThatIsSynonymousToCall1 =
                when( $DOMDocument->cloneNode( true ) )
                        ->thenReturn( 'FOO' );

        $usageTestClass = new UsageTestClass( $DOMDocument );
        $this->assertEquals( 'MOO', $usageTestClass->testTrue(), 'a' );
        $this->assertEquals( 'FOO', $usageTestClass->testTrue(), 'b' );
        $this->assertEquals( 'Baaa', $usageTestClass->testDefault() );
        $this->assertEquals( 'Baaa', $usageTestClass->testManualDefault() );

        $this->assertEquals(
                'FOO',
                $DOMDocument->cloneNode( true ),
                'Returns the mocked value when called in the test'
        );

        verify( $DOMDocument, 2 )->cloneNode( true );
        verify( $DOMDocument, 2 )->cloneNode();
        verify( $DOMDocument, 2 )->cloneNode( null );

        verifyMethodCall( $methodCall1, 2 );
        verifyMethodCall( $methodCall2, 2 );
        verifyMethodCall( $methodCall3ThatIsSynonymousToCall1, 2 );
    }


    public function test_mock_magic_call_returnValue() {
        $usageTestClass       = new UsageTestClass( mock( '\DomDocument' ) );
        $magicMethodTestClass = mock( MagicMethodTestClass::CLASS_NAME );

        $magicMethodCallResult   = 'magicMethodCall result';
        $fullyActionedMethodCall =
                when( $magicMethodTestClass->__call( 'magicMethodCall', array( 'testValue' ) ) )
                        ->thenReturn( $magicMethodCallResult );

        $this->assertEquals( $magicMethodCallResult, $usageTestClass->testMagicMethods( $magicMethodTestClass ) );

        verifyMethodCall( $fullyActionedMethodCall );
        verify( $magicMethodTestClass )->__call( 'magicMethodCall', array( 'testValue' ) );
        verifyNoMoreInteractions( $magicMethodTestClass );
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


    public function test_spy_returnValue() {
        $DOMDocument = spy( '\DomDocument' );
        $methodCall1 = when( $DOMDocument->cloneNode( true ) )
                ->thenReturn( 'MOO' );

        $methodCall2 = when( $DOMDocument->cloneNode( null ) )
                ->thenReturn( 'Baaa' );

        $usageTestClass = new UsageTestClass( $DOMDocument );
        $this->assertEquals( 'MOO', $usageTestClass->testTrue() );
        $this->assertEquals( 'Baaa', $usageTestClass->testDefault() );
        $this->assertEquals( 'Baaa', $usageTestClass->testManualDefault() );


        $expectedXml = <<<XML
<?xml version="1.0"?>
<xml/>
XML;

        $this->assertEquals( $expectedXml, trim( $usageTestClass->testSpyParentCall() ) );

        verify( $DOMDocument, 1 )->cloneNode( true );
        verify( $DOMDocument, 2 )->cloneNode();
        verify( $DOMDocument, 2 )->cloneNode( null );

        verifyMethodCall( $methodCall1 );
        verifyMethodCall( $methodCall2, 2 );
        verifyNoMoreInteractions( $DOMDocument );
    }
}
 