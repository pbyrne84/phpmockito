<?php
namespace PHPMockito\Mock;

use PHPMockito\Mock\Method\MockMethodCodeGenerator;

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
            new MockMethodCodeGenerator(),
            array()
        );

        $expectedMockCode = <<<'PHP'
namespace {
    use \PHPMockito\Mock\MockedClass;
    use \PHPMockito\Mock\MockedClassConstructorParams;
    use \PHPMockito\Action\DebugBackTraceMethodCall;

    class TestMock extends DOMDocument  implements  MockedClass {
        private $mockedClassConstructorParams;
        private $defaultValueMap = array(

        );

        function __construct( MockedClassConstructorParams $mockedClassConstructorParams ){
            $this->mockedClassConstructorParams = $mockedClassConstructorParams;
        }


        public function getInstanceReference(){
            return $this->mockedClassConstructorParams->getInstanceReference();
        }


        public function getMethodsDefaultParameterMap( $methodName ){
            if( !$this->hasMockedMethod( $methodName ) ){
                throw new \InvalidArgumentException( 'Default values not set for method ' . $methodName );
            }

            return $this->defaultValueMap[ $methodName ];
        }


        public function hasMockedMethod( $methodName ){
            return array_key_exists( $methodName, $this->defaultValueMap );
        }


    }
}
PHP;

        $this->assertEquals( $expectedMockCode, $actualMockCode );
    }


    public function test_createMockCode_multipleMethods() {
        $mockedMethodList = array(
            new MockedMethod( new \ReflectionMethod( '\DomDocument', 'appendChild' ) ),
            new MockedMethod( new \ReflectionMethod( '\DomDocument', 'importNode' ) )
        );

        $actualMockCode = $this->mockClassCodeGenerator->createMockCode(
            'TestMock',
            new \ReflectionClass( '\DomDocument' ),
            new MockMethodCodeGenerator(),
            $mockedMethodList
        );

        $expectedMockCode = <<<'PHP'
namespace {
    use \PHPMockito\Mock\MockedClass;
    use \PHPMockito\Mock\MockedClassConstructorParams;
    use \PHPMockito\Action\DebugBackTraceMethodCall;

    class TestMock extends DOMDocument  implements  MockedClass {
        private $mockedClassConstructorParams;
        private $defaultValueMap = array(
            'appendChild' => array(0=> null),
            'importNode' => array(0=> null,1=> null),

        );

        function __construct( MockedClassConstructorParams $mockedClassConstructorParams ){
            $this->mockedClassConstructorParams = $mockedClassConstructorParams;
        }


        public function getInstanceReference(){
            return $this->mockedClassConstructorParams->getInstanceReference();
        }


        public function getMethodsDefaultParameterMap( $methodName ){
            if( !$this->hasMockedMethod( $methodName ) ){
                throw new \InvalidArgumentException( 'Default values not set for method ' . $methodName );
            }

            return $this->defaultValueMap[ $methodName ];
        }


        public function hasMockedMethod( $methodName ){
            return array_key_exists( $methodName, $this->defaultValueMap );
        }


        public function appendChild( \DOMNode $newChild = null ) {
            $methodCall = new DebugBackTraceMethodCall(
                $this->mockedClassConstructorParams->getToStringAdaptorFactory(),
                $this,
                'appendChild',
                count(array($newChild)) < count( func_get_args() ) ? func_get_args() :  array($newChild),
                debug_backtrace()
            );

            $this->mockedClassConstructorParams->registerCall( $methodCall );
            $actionedCall = $this->mockedClassConstructorParams->actionCall( $methodCall );

            return $actionedCall;
        }

        public function importNode( \DOMNode $importedNode = null, $deep = null ) {
            $methodCall = new DebugBackTraceMethodCall(
                $this->mockedClassConstructorParams->getToStringAdaptorFactory(),
                $this,
                'importNode',
                count(array($importedNode, $deep)) < count( func_get_args() ) ? func_get_args() :  array($importedNode, $deep),
                debug_backtrace()
            );

            $this->mockedClassConstructorParams->registerCall( $methodCall );
            $actionedCall = $this->mockedClassConstructorParams->actionCall( $methodCall );

            return $actionedCall;
        }
    }
}
PHP;

        $this->assertEquals( $expectedMockCode, $actualMockCode );
    }


}
  
