<?php

namespace PHPMockito\Mock;


class MockClassCodeGenerator {
    const CLASS_NAME = __CLASS__;


    /**
     * @param string                  $mockShortClassName
     * @param \ReflectionClass        $reflectionClass
     * @param array|MockedParameter[] $mockedMethodList
     *
     * @return string
     */
    public function createMockCode( $mockShortClassName, \ReflectionClass $reflectionClass, array $mockedMethodList ) {
        $namespace = $reflectionClass->getNamespaceName();

        $defaultValueMap = $this->convertMethodListToClassMethodsDefaultParameterMap( $mockedMethodList );
        $methodCode = $this->convertMethodListToMethodCode( $mockedMethodList );

        $mockCode = <<<TEXT
namespace {$namespace}{
    use \PHPMockito\Mock\MockedClass;
    use \PHPMockito\Mock\MockedClassConstructorParams;
    use \PHPMockito\Action\DebugBackTraceMethodCall;

    class $mockShortClassName extends {$reflectionClass->getShortName()} implements MockedClass {
        private \$mockedClassConstructorParams;
        private \$defaultValueMap = array(
$defaultValueMap
        );

        function __construct( MockedClassConstructorParams \$mockedClassConstructorParams ){
            \$this->mockedClassConstructorParams = \$mockedClassConstructorParams;
        }


        public function getInstanceReference(){
            return \$this->mockedClassConstructorParams->getInstanceReference();
        }


        public function getMethodsDefaultParameterMap( \$methodName ){
            if( !array_key_exists( \$methodName, \$this->defaultValueMap ) ){
                throw new \InvalidArgumentException( 'Default values not set for method ' . \$methodName );
            }

            return \$this->defaultValueMap[ \$methodName ];
        }

{$methodCode}
    }
}
TEXT;

        return $mockCode;
    }


    /**
     * @param array|MockedMethod[] $mockedMethodList
     *
     * @return string
     */
    private function convertMethodListToMethodCode( array $mockedMethodList ) {
        $code = '';
        foreach ( $mockedMethodList as $mockedMethod ) {
            $code .= <<<TXT

        {$mockedMethod->getVisibilityAsString()} function {$mockedMethod->getName()}( {$mockedMethod->getSignature()} ) {
            \$methodCall = new DebugBackTraceMethodCall(
                \$this, '{$mockedMethod->getName()}', {$mockedMethod->getParameterArrayEntrapment()}, debug_backtrace()
            );
            return \$this->mockedClassConstructorParams->actionCall( \$methodCall );
        }

TXT;
        }

        return rtrim( trim( $code, ' ' ) );
    }

    /**
     * @param array|MockedMethod[] $mockedMethodList
     *
     * @return string
     */
    private function convertMethodListToClassMethodsDefaultParameterMap(  array $mockedMethodList ){
        $defaultValueCode = '';
        foreach ( $mockedMethodList as $mockedMethod ) {
            $mapCode = 'array(';
            foreach ( $mockedMethod->getOptionalArgumentMap() as $index => $mockedArgument) {
                $mapCode .=  $index . '=> ' . print_r( $mockedArgument, true ) . ',';
            }

            $mapCode = rtrim( $mapCode, "," );
            $mapCode.= '),';
           // $mapCode = $mockedMethod->getOptionalArgumentMap();

            $defaultValueCode .= <<<TXT
            '{$mockedMethod->getName()}' => {$mapCode}

TXT;
        }


        return $defaultValueCode;

    }
}
