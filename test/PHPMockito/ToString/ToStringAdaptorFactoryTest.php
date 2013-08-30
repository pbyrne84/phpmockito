<?php
namespace PHPMockito\ToString;

class ToStringAdaptorFactoryTest extends \PHPUnit_Framework_TestCase {

    const CLASS_NAME = __CLASS__;

    /**
     * @var ToStringAdaptorFactory
     */
    private $toStringAdaptorFactory;


    protected function setUp() {
        $this->toStringAdaptorFactory = new ToStringAdaptorFactory();
    }


    public function test_string() {
        $adaptor = $this->toStringAdaptorFactory->createToStringAdaptor( 'string value' );
        $this->assertEquals( "'string value'", $adaptor->toString() );
    }


    public function test_int() {
        $adaptor = $this->toStringAdaptorFactory->createToStringAdaptor( 1 );
        $this->assertEquals( '1', $adaptor->toString() );
    }


    public function test_array() {
        $adaptor = $this->toStringAdaptorFactory->createToStringAdaptor( array( 1, 2, 3 ) );
        $this->assertEquals( '1', $adaptor->toString() );
    }


    public function test_DomDocument() {
        $DOMDocument = new \DOMDocument();
        $DOMDocument->loadXML( '<test_xml/>' );

        $adaptor = $this->toStringAdaptorFactory->createToStringAdaptor( $DOMDocument );
        $this->assertEquals( '1', $adaptor->toString() );
    }


    public function test_genericClass() {
        $DOMDocument = new \DOMDocument();
        $DOMDocument->loadXML( '<test_xml/>' );

        $stdClass    = new \stdClass();
        $stdClass->property_a = 'a';
        $stdClass->property_b = 1;
        $stdClass->property_c = array( 1, 2345, 3456556 );
        $stdClass->property_d = $DOMDocument;
        $stdClass->property_e = new \SplFileInfo("c:/monkey.txt");
        $stdClass->property_f = new \RuntimeException("I am a runtime exception");
        $stdClass->property_g = mock( '\RuntimeException' );

        $adaptor = $this->toStringAdaptorFactory->createToStringAdaptor( $stdClass );
        $this->assertEquals( '1', $adaptor->toString() );
    }


}
  
