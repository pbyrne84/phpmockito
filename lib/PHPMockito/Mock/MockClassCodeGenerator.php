<?php

namespace PHPMockito\Mock;


use PHPMockito\Mock\Method\MethodCodeGenerator;
use PHPMockito\Mock\Method\MockMethodCodeGenerator;

class MockClassCodeGenerator {
    

    /**
     * @param string                     $mockShortClassName
     * @param \ReflectionClass           $reflectionClass
     * @param Method\MethodCodeGenerator $methodCodeGenerator
     * @param array|MockedParameter[]    $mockedMethodList
     *
     * @return string
     */
    public function createMockCode( $mockShortClassName,
                                    \ReflectionClass $reflectionClass,
                                    MethodCodeGenerator $methodCodeGenerator,
                                    array $mockedMethodList ) {
        $namespace = $reflectionClass->getNamespaceName();

        $defaultValueMap = $this->convertMethodListToClassMethodsDefaultParameterMap( $mockedMethodList );
        $methodCode      = $this->convertMethodListToMethodCode( $methodCodeGenerator, $mockedMethodList );

        if ( $reflectionClass->isInterface() ) {
            $substitutionKeyword    = 'implements';
            $mockInterfaceSeparator = ', ';
        } else {
            $substitutionKeyword    = 'extends';
            $mockInterfaceSeparator = ' implements ';
        }

        $mockCode = <<<TEXT
namespace {$namespace}{
    use \PHPMockito\Mock\MockedClass;
    use \PHPMockito\Mock\MockedClassConstructorParams;
    use \PHPMockito\Action\DebugBackTraceMethodCall;

    class $mockShortClassName {$substitutionKeyword} {$reflectionClass->getShortName()} {$mockInterfaceSeparator} MockedClass {
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
            if( !\$this->hasMockedMethod( \$methodName ) ){
                throw new \InvalidArgumentException( 'Default values not set for method ' . \$methodName );
            }

            return \$this->defaultValueMap[ \$methodName ];
        }


        public function hasMockedMethod( \$methodName ){
            return array_key_exists( \$methodName, \$this->defaultValueMap );
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
    private function convertMethodListToClassMethodsDefaultParameterMap( array $mockedMethodList ) {
        $defaultValueCode = '';
        foreach ( $mockedMethodList as $mockedMethod ) {
            $mapCode = 'array(';
            foreach ( $mockedMethod->getOptionalArgumentMap() as $index => $mockedArgument ) {
                $mapCode .= $index . '=> ' . print_r( $mockedArgument, true ) . ',';
            }

            $mapCode = rtrim( $mapCode, "," );
            $mapCode .= '),';
            // $mapCode = $mockedMethod->getOptionalArgumentMap();

            $defaultValueCode .= <<<TXT
            '{$mockedMethod->getName()}' => {$mapCode}

TXT;
        }

        return $defaultValueCode;

    }


    /**
     * @param Method\MethodCodeGenerator $methodCodeGenerator
     * @param array|MockedMethod[]       $mockedMethodList
     *
     * @return string
     */
    private function convertMethodListToMethodCode( MethodCodeGenerator $methodCodeGenerator,
                                                    array $mockedMethodList ) {
        $code = '';
        foreach ( $mockedMethodList as $mockedMethod ) {
            $code .= $methodCodeGenerator->generateMethodCode( $mockedMethod );
        }

        return rtrim( trim( $code, ' ' ) );
    }
}
