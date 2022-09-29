<?php
namespace PHPMockito\EndToEnd;

use PHPMockito\TestClass\MagicMethodTestClass;
use PHPMockito\TestClass\UsageTestClass;
use PHPUnit\Framework\TestCase;

class MockitoBasicEndToEndTest extends TestCase {
    

    protected function setUp() {
    }


    public function test_mock_returnValue() {
        $DOMDocument = mock( '\DomDocument' );

        $methodCall1 =
                when( $DOMDocument->cloneNode( true ) )
                        ->thenReturn( 'MOO' )
                        ->thenReturn( 'FOO' )
                        ->thenReturn( 'GOO' );

        $methodCall2 =
                when( $DOMDocument->cloneNode( null ) )
                        ->thenReturn( 'Baaa' );

        $usageTestClass = new UsageTestClass( $DOMDocument );
        $this->assertEquals( 'MOO', $usageTestClass->testTrue(), 'a' );
        $this->assertEquals( 'FOO', $usageTestClass->testTrue(), 'b' );
        $this->assertEquals( 'Baaa', $usageTestClass->testDefault() );
        $this->assertEquals( 'Baaa', $usageTestClass->testManualDefault() );

        $this->assertEquals(
                'GOO',
                $DOMDocument->cloneNode( true ),
                'Returns the mocked value when called in the test'
        );

        verify( $DOMDocument, 2 )->cloneNode( true );
        verify( $DOMDocument, 2 )->cloneNode();
        verify( $DOMDocument, 2 )->cloneNode( null );

        verifyMethodCall( $methodCall1, 2 );
        verifyMethodCall( $methodCall2, 2 );
    }


    public function test_mock_magic_call_returnValue() {
        $usageTestClass       = new UsageTestClass( mock( '\DomDocument' ) );
        $magicMethodTestClass = mock( MagicMethodTestClass::class );

        $magicMethodCallResult   = 'magicMethodCall result';
        $fullyActionedMethodCall =
                when( $magicMethodTestClass->__call( 'magicMethodCall', array( 'testValue' ) ) )
                        ->thenReturn( $magicMethodCallResult );

        $this->assertEquals( $magicMethodCallResult, $usageTestClass->testMagicMethods( $magicMethodTestClass ) );

        verifyMethodCall( $fullyActionedMethodCall );
        verify( $magicMethodTestClass )->__call( 'magicMethodCall', array( 'testValue' ) );
        verifyNoMoreInteractions( $magicMethodTestClass );
    }


    public function test_mock_exception() {
        $DOMDocument = mock( '\DomDocument');
        when( $DOMDocument->cloneNode( true ) )
                ->thenThrow( new TestException("xx") );

        $usageTestClass = new UsageTestClass( $DOMDocument );

        $this->expectException(TestException::class);
        $usageTestClass->testTrue();
    }


    //Never used spies so not really implemented
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
 