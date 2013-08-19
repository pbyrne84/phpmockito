<?php

namespace PHPMockito\Caster;


class DomDocumentValueCaster implements ValueCaster {
    const CLASS_NAME = __CLASS__;

    /** @var \DOMDocument */
    private $domDocument;

    /** @var string */
    private $originalType;


    /**
     * @param \DOMDocument $domDocument
     */
    function __construct( \DOMDocument $domDocument ) {
        $this->domDocument  = $domDocument;
        $this->originalType = get_class( $domDocument );
    }


    public function getOriginalType() {
        return $this->originalType;
    }


    /**
     * @return string
     */
    public function toComparableString() {
        $xml                              = $this->domDocument->saveXML();
        $oDomDocument                     = new \DOMDocument( '1.0' );
        $oDomDocument->preserveWhiteSpace = false;
        $oDomDocument->formatOutput       = true;
        $oDomDocument->loadXML( $xml );

        return $oDomDocument->saveXML();

    }
}
 