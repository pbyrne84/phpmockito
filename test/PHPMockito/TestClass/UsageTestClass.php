<?php

namespace PHPMockito\TestClass;


class UsageTestClass {
    const CLASS_NAME = __CLASS__;

    /** @var \DOMDocument */
    private $domDocument;


    /**
     * @param \DOMDocument $domDocument
     */
    function __construct( \DOMDocument $domDocument) {
        $this->domDocument = $domDocument;
    }



    public function testTrue(){
        return $this->domDocument->cloneNode( true );
    }

    public function testFalse(){
        return $this->domDocument->cloneNode( );
    }



}
 