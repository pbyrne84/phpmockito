<?php
namespace PHPMockito\Mock;

use http\Exception\UnexpectedValueException;
use PHPUnit\Framework\TestCase;
use PHPMockito\TestClass\SignatureTestClass;

class MockedMethodTest extends TestCase {

    

    protected function setUp() : void{

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
        $reflectionMethod = new \ReflectionMethod( SignatureTestClass::class, 'testMethodAllRequired' );
        $mockedMethod     = new MockedMethod( $reflectionMethod );

        $this->assertEquals(
            '\DOMDocument $domDocument1, array $requiredArray, $nonTypeHint, \Closure $closure1',
            $mockedMethod->getSignature(),
            'signature matches'
        );
    }


    public function test_getSignature_customMethods_multipleVariations_allOptional() {
        $reflectionMethod = new \ReflectionMethod( SignatureTestClass::class, 'testMethodAllOptional' );
        $mockedMethod     = new MockedMethod( $reflectionMethod );

        $this->assertEquals(
            '\DOMDocument $domDocument1 = NULL, array $requiredArray = array(), $nonTypeHint = \'\', \Closure $closure1 = NULL',
            $mockedMethod->getSignature(),
            'signature matches'
        );
    }


    public function test_getCommaSeparatedArguments_hasParameters() {
        $reflectionMethod     = mock( '\ReflectionMethod' );
        $reflectionParameter1 = $this->createReflectionParameter( true, 'parameterA' );
        $reflectionParameter2 = $this->createReflectionParameter( false, 'parameterB' );
        $reflectionParameter3 = $this->createReflectionParameter( true, 'parameterC' );

        when( $reflectionMethod->getParameters() )
                ->thenReturn( array( $reflectionParameter1, $reflectionParameter2, $reflectionParameter3 ) );

        $mockedMethod = new MockedMethod( $reflectionMethod );
        $this->assertEquals(
            '$parameterA, $parameterB, $parameterC',
            $mockedMethod->getCommaSeparatedArguments()
        );
    }


    /**
     * @param boolean $owningParameterClassIsInternal
     *
     * @param string  $name
     *
     * @internal param string $typHint
     * @return \ReflectionParameter
     */
    private function createReflectionParameter( $owningParameterClassIsInternal, $name ) {
        $parentClass = mock( '\ReflectionClass' );

        $reflectionParameter = mock( '\ReflectionParameter' );
        when( $reflectionParameter->getDeclaringClass() )
                ->thenReturn( $parentClass );

        when( $reflectionParameter->getName() )
                ->thenReturn( $name );

        when( $parentClass->isInternal() )
                ->thenReturn( $owningParameterClassIsInternal );

        return $reflectionParameter;
    }


    public function test_getCommaSeparatedArguments_noParameters() {
        $reflectionMethod = mock( '\ReflectionMethod' );

        when( $reflectionMethod->getParameters() )
                ->thenReturn( array() );

        $mockedMethod = new MockedMethod( $reflectionMethod );
        $this->assertEquals(
            '',
            $mockedMethod->getCommaSeparatedArguments()
        );
    }


    public function test_getVisibilityAsString_public() {
        $reflectionMethod     = mock( '\ReflectionMethod' );
        when( $reflectionMethod->getParameters() )
                ->thenReturn( array( ) );

        when( $reflectionMethod->isPublic() )
                ->thenReturn( true );

        $mockedMethod = new MockedMethod( $reflectionMethod );
        $this->assertEquals(
            'public',
            $mockedMethod->getVisibilityAsString()
        );
    }


    public function test_getVisibilityAsString_protected() {
        $reflectionMethod     = mock( '\ReflectionMethod' );
        when( $reflectionMethod->getParameters() )
                ->thenReturn( array( ) );

        when( $reflectionMethod->isProtected() )
                ->thenReturn( true );

        $mockedMethod = new MockedMethod( $reflectionMethod );
        $this->assertEquals(
            'protected',
            $mockedMethod->getVisibilityAsString()
        );
    }



    public function test_getVisibilityAsString_defaultEmpty() {
        $reflectionMethod     = mock( '\ReflectionMethod' );
        when( $reflectionMethod->getParameters() )
                ->thenReturn( array( ) );

        $mockedMethod = new MockedMethod( $reflectionMethod );
        $this->assertEquals(
            '',
            $mockedMethod->getVisibilityAsString()
        );
    }

    
    public function test_getVisibilityAsString_privateThrowsException() {
        $reflectionMethod = mock( '\ReflectionMethod' );
        when( $reflectionMethod->getParameters() )
                ->thenReturn( array() );

        when( $reflectionMethod->isPrivate() )
                ->thenReturn( true );

        $mockedMethod = new MockedMethod( $reflectionMethod );
        $this->expectException(\UnexpectedValueException::class);
        $this->assertEquals(
            '',
            $mockedMethod->getVisibilityAsString()
        );
    }
}

