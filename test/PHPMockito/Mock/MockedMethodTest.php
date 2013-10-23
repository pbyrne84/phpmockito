<?php
namespace PHPMockito\Mock;

use PHPMockito\TestClass\SignatureTestClass;

class MockedMethodTest extends \PHPUnit_Framework_TestCase {

    const CLASS_NAME = __CLASS__;


    protected function setUp() {

    }


    public function test_getName_singleTypeHintedVariable() {
        $reflectionMethod = new \ReflectionMethod( '\DomDocument', 'appendChild' );
        $mockedMethod     = new MockedMethod( $reflectionMethod );

        $this->assertEquals( 'appendChild', $mockedMethod->getName() );
    }


    public function test_getSignature_singleTypeHinted() {
        $reflectionMethod = new \ReflectionMethod( '\DomDocument', 'appendChild' );
        $mockedMethod     = new MockedMethod( $reflectionMethod );

        $this->assertEquals( '\DOMNode $newChild = null', $mockedMethod->getSignature() );
    }


    public function test_getSignature_internalMethodsDeferToDefaultIsNull_multipleVariations() {
        $reflectionMethod = new \ReflectionMethod( '\DOMImplementation', 'createDocument' );
        $mockedMethod     = new MockedMethod( $reflectionMethod );

        $this->assertEquals(
            '$namespaceURI = null, $qualifiedName = null, \DOMDocumentType $docType = null',
            $mockedMethod->getSignature(),
            'signature matches'
        );
    }


    public function test_getSignature_customMethods_multipleVariations_allRequired() {
        $reflectionMethod = new \ReflectionMethod( SignatureTestClass::CLASS_NAME, 'testMethodAllRequired' );
        $mockedMethod     = new MockedMethod( $reflectionMethod );

        $this->assertEquals(
            '\DOMDocument $domDocument1, array $requiredArray, $nonTypeHint, \Closure $closure1',
            $mockedMethod->getSignature(),
            'signature matches'
        );
    }


    public function test_getSignature_customMethods_multipleVariations_allOptional() {
        $reflectionMethod = new \ReflectionMethod( SignatureTestClass::CLASS_NAME, 'testMethodAllOptional' );
        $mockedMethod     = new MockedMethod( $reflectionMethod );

        $this->assertEquals(
            '\DOMDocument $domDocument1 = NULL, array $requiredArray = array(), $nonTypeHint = \'\', \Closure $closure1 = NULL',
            $mockedMethod->getSignature(),
            'signature matches'
        );
    }
}

