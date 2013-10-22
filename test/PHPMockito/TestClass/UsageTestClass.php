<?php

namespace PHPMockito\TestClass;


class UsageTestClass {
    const CLASS_NAME = __CLASS__;

    /** @var \DOMDocument */
    private $domDocument;


    /**
     * @param \DOMDocument $domDocument
     */
    function __construct( \DOMDocument $domDocument ) {
        $this->domDocument = $domDocument;
    }


    public function testTrue() {
        return $this->domDocument->cloneNode( true );
    }


    public function testDefault() {
        return $this->domDocument->cloneNode();
    }


    public function testManualDefault() {
        return $this->domDocument->cloneNode( null );
    }


    public function testSpyParentCall() {
        $this->domDocument->loadXML( '<xml/>' );

        return $this->domDocument->saveXML();
    }


    /**
     * @param MagicMethodTestClass $magicMethodTestClass
     *
     * @return string
     */
    public function testMagicMethods( MagicMethodTestClass $magicMethodTestClass ) {
        return $magicMethodTestClass->__call( 'magicMethodCall', array( 'testValue' ) );
    }


    protected function testMoo() {
        return '';
    }
}
 