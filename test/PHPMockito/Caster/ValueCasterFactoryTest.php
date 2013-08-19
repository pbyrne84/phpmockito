<?php
namespace PHPMockito\Caster;

use PHPMockito\Run\Mockito;

class ValueCasterFactoryTest extends \PHPUnit_Framework_TestCase {

    const CLASS_NAME = __CLASS__;

    /**
     * @var ValueCasterFactory
     */
    private $valueCasterFactory;


    protected function setUp() {
        $this->valueCasterFactory = new ValueCasterFactory();
    }


    public function test_castValueToComparableType_mock() {
        $DOMDocument = Mockito::mock('\DomDocument');
        $this->assertEquals(
            MockValueCaster::CLASS_NAME,
            get_class( $this->valueCasterFactory->castValueToComparableType( $DOMDocument ) )
        );
    }


    public function test_castValueToComparableType_domDocument() {
        $DOMDocument = new \DOMDocument();
        $this->assertEquals(
            DomDocumentValueCaster::CLASS_NAME,
            get_class( $this->valueCasterFactory->castValueToComparableType( $DOMDocument ) )
        );
    }


    public function test_castValueToComparableType_splFileInfo() {
        $splFileInfo = new \SplFileInfo( '' );
        $this->assertEquals(
            SplFileInfoValueCaster::CLASS_NAME,
            get_class( $this->valueCasterFactory->castValueToComparableType( $splFileInfo ) )
        );
    }


    public function test_castValueToComparableType_nonObject() {
        $this->assertEquals(
            VarExportingValueCaster::CLASS_NAME,
            get_class( $this->valueCasterFactory->castValueToComparableType( 'string' ) )
        );
    }


    public function test_castValueToComparableType_nonCustomClass() {
        $this->assertEquals(
            VarExportingValueCaster::CLASS_NAME,
            get_class( $this->valueCasterFactory->castValueToComparableType(  new \stdClass() )  )
        );
    }

}
  
