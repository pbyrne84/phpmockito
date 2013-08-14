<?php
namespace PHPMockito\Mock;

class MockClassCodeGeneratorTest extends \PHPUnit_Framework_TestCase {

    const CLASS_NAME = __CLASS__;

    /**
     * @var MockClassCodeGenerator
     */
    private $mockClassCodeGenerator;


    protected function setUp() {
        $this->mockClassCodeGenerator = new MockClassCodeGenerator();
    }


    public function test_createMockCode_noMethods() {
        $actualMockCode = $this->mockClassCodeGenerator->createMockCode(
            'TestMock',
            new \ReflectionClass( '\DomDocument' ),
            array()
        );

        $expectedMockCode = <<<'PHP'
namespace {
    use \PHPMockito\Mock\MockedClass;
    use \PHPMockito\Mock\MockedClassConstructorParams;
    use \PHPMockito\Action\MethodCall;

    class TestMock extends DOMDocument implements MockedClass {
        private $mockedClassConstructorParams;

        function __construct( MockedClassConstructorParams $mockedClassConstructorParams ){
            $this->mockedClassConstructorParams = $mockedClassConstructorParams;
        }

        public function getInstanceReference(){
            return $this->mockedClassConstructorParams->getInstanceReference();
        }

    }
}
PHP;

        $this->assertEquals( $expectedMockCode, $actualMockCode );
    }



    public function test_createMockCode_multipleMethods(){
        $mockedMethodList = array(
            new MockedMethod( new \ReflectionMethod( '\DomDocument', 'appendChild') ),
            new MockedMethod( new \ReflectionMethod( '\DomDocument', 'importNode') )
        );

        $actualMockCode = $this->mockClassCodeGenerator->createMockCode(
            'TestMock',
            new \ReflectionClass( '\DomDocument' ),
            $mockedMethodList
        );

        $expectedMockCode = <<<'PHP'
namespace {
    use \PHPMockito\Mock\MockedClass;
    use \PHPMockito\Mock\MockedClassConstructorParams;
    use \PHPMockito\Action\MethodCall;

    class TestMock extends DOMDocument implements MockedClass {
        private $mockedClassConstructorParams;

        function __construct( MockedClassConstructorParams $mockedClassConstructorParams ){
            $this->mockedClassConstructorParams = $mockedClassConstructorParams;
        }

        public function getInstanceReference(){
            return $this->mockedClassConstructorParams->getInstanceReference();
        }

        public function appendChild( \DOMNode $newChild = NULL ) {
            $methodCall = new MethodCall( $this, 'appendChild', func_get_args(), debug_backtrace() );
            return $this->mockedClassConstructorParams->actionCall( $methodCall );
        }

        public function importNode( \DOMNode $importedNode = NULL,  $deep = NULL ) {
            $methodCall = new MethodCall( $this, 'importNode', func_get_args(), debug_backtrace() );
            return $this->mockedClassConstructorParams->actionCall( $methodCall );
        }
    }
}
PHP;

        $this->assertEquals( $expectedMockCode, $actualMockCode );
    }




}
  
