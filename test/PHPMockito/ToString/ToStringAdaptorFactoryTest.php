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
        $stdClass->a = 'a';
        $stdClass->b = 1;
        $stdClass->c = array( 1, 2, 3 );
        $stdClass->d = $DOMDocument;

        $adaptor = $this->toStringAdaptorFactory->createToStringAdaptor( $stdClass );
        $this->assertEquals( '1', $adaptor->toString() );
    }


}
  
