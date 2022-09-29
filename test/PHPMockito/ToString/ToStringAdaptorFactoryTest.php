<?php
namespace PHPMockito\ToString;

use PHPMockito\Mock\MockedClass;
use PHPUnit\Framework\TestCase;

class ToStringAdaptorFactoryTest extends TestCase {

    
    /**
     * @var ToStringAdaptorFactory
     */
    private $toStringAdaptorFactory;


    protected function setUp() : void{
        $this->toStringAdaptorFactory = new ToStringAdaptorFactory();
    }


    public function test_string() {
        $adaptor = $this->toStringAdaptorFactory->createToStringAdaptor( 'string value' );
        $this->assertEquals( "string(12) 'string value'", $adaptor->toString() );
    }


    public function test_int() {
        $adaptor = $this->toStringAdaptorFactory->createToStringAdaptor( 1 );
        $this->assertEquals( 'integer(1) 1', $adaptor->toString() );
    }


    public function test_array() {
        $expected = <<<TXT
array  (
    [0] : integer(1) 1
    [1] : integer(1) 2
    [2] : integer(1) 3
)
TXT;

        $adaptor = $this->toStringAdaptorFactory->createToStringAdaptor( array( 1, 2, 3 ) );
        $this->assertEquals( $expected, $adaptor->toString() );
    }


    public function test_DomDocument() {
        $expected = <<<TXT
DOMDocument(34) '<?xml version="1.0"?>
<test_xml/>
'
TXT;

        $DOMDocument = new \DOMDocument();
        $DOMDocument->loadXML( '<test_xml/>' );

        $adaptor = $this->toStringAdaptorFactory->createToStringAdaptor( $DOMDocument );
        $this->assertEquals( $expected, $adaptor->toString() );
    }


    public function test_genericClass() {
        /** @var $runtimeException \RuntimeException|MockedClass */
        $runtimeException = mock( '\RuntimeException' );

        $expected = <<<TXT
stdClass      (
        [property_a] : string(1) 'a'
        [property_b] : integer(1) 1
        [property_c] : array          (
                [0] : integer(1) 1
                [1] : integer(4) 2345
                [2] : integer(7) 3456556
            )
        [property_d] : DOMDocument(34) '<?xml version="1.0"?>
    <test_xml/>
    '
        [property_e] : SplFileInfo(13) 'c:/monkey.txt'
        [property_f] : RuntimeException(24) 'I am a runtime exception"
        [property_g] : RuntimeException_PhpMockitoMock ({$runtimeException->getInstanceReference()})
    )
TXT;

        $DOMDocument = new \DOMDocument();
        $DOMDocument->loadXML( '<test_xml/>' );

        $stdClass    = new \stdClass();
        $stdClass->property_a = 'a';
        $stdClass->property_b = 1;
        $stdClass->property_c = array( 1, 2345, 3456556 );
        $stdClass->property_d = $DOMDocument;
        $stdClass->property_e = new \SplFileInfo("c:/monkey.txt");
        $stdClass->property_f = new \RuntimeException("I am a runtime exception");
        $stdClass->property_g = $runtimeException;

        $adaptor = $this->toStringAdaptorFactory->createToStringAdaptor( $stdClass );
        $this->assertEquals( $expected, $adaptor->toString() );
    }


}
  
