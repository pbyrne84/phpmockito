<?php

namespace PHPMockito\ToString;


class DomDocumentToStringAdaptor extends  ToStringAdaptor{
    const CLASS_NAME = __CLASS__;

    /** @var \DOMDocument */
    private $domDocument;


    /**
     * @param \DOMDocument $document
     */
    function __construct( \DOMDocument $document ) {
        $this->domDocument = $document;
    }


    function toString( $indentation = 0 ) {
        $xml                              = $this->domDocument->saveXML();
        $oDomDocument                     = new \DOMDocument( '1.0' );
        $oDomDocument->preserveWhiteSpace = false;
        $oDomDocument->formatOutput       = true;
        $oDomDocument->loadXML( $xml );

        return $oDomDocument->saveXML();
    }
}
 