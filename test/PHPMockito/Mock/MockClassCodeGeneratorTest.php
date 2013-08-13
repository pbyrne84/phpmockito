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
    use \PHPMockito\Action\MethodCall;
    use \PHPMockito\Action\MethodCallListener;

    class TestMock extends DOMDocument implements MockedClass {
        private $methodCallListener;

        function __construct( MethodCallListener $methodCallListener ){
            $this->methodCallListener = $methodCallListener;
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
    use \PHPMockito\Action\MethodCall;
    use \PHPMockito\Action\MethodCallListener;

    class TestMock extends DOMDocument implements MockedClass {
        private $methodCallListener;

        function __construct( MethodCallListener $methodCallListener ){
            $this->methodCallListener = $methodCallListener;
        }

        public function appendChild( \DOMNode $newChild = NULL ) {
            $methodCall = new MethodCall( $this, 'appendChild', func_get_args() );
            return $this->methodCallListener->actionCall( $methodCall );
        }

        public function importNode( \DOMNode $importedNode = NULL,  $deep = NULL ) {
            $methodCall = new MethodCall( $this, 'importNode', func_get_args() );
            return $this->methodCallListener->actionCall( $methodCall );
        }
    }
}
PHP;


        $this->assertEquals( $expectedMockCode, $actualMockCode );
    }




}
  
