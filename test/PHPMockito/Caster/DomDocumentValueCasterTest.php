<?php
namespace PHPMockito\Caster;

class DomDocumentValueCasterTest extends ValueCasterTest {

    const CLASS_NAME = __CLASS__;


    protected function setUp() {

    }


    public function test_matchingDocument() {
        $originalXml = <<<XML
<xml>
  <value1>test value</value1>  <value2>test value</value2>
</xml>
XML;

        $DOMDocument = new \DOMDocument();
        $DOMDocument->loadXML( $originalXml );

        $domDocumentValueCaster = new DomDocumentValueCaster( $DOMDocument );
        $expectedXml            = <<<XML
<?xml version="1.0"?>
<xml>
  <value1>test value</value1>
  <value2>test value</value2>
</xml>

XML;
        $actualMap              = $this->createComparableMap(
            $domDocumentValueCaster->getOriginalType(),
            $domDocumentValueCaster->toComparableString()
        );

        $this->assertEquals(
            $this->createComparableMap( 'DOMDocument', $expectedXml ),
            $actualMap
        );


    }

}
  
