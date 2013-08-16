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

        $methodCode = $this->convertMethodListToCode( $mockedMethodList );

        $mockCode = <<<TEXT
namespace {$namespace}{
    use \PHPMockito\Mock\MockedClass;
    use \PHPMockito\Mock\MockedClassConstructorParams;
    use \PHPMockito\Action\DebugBackTraceMethodCall;

    class $mockShortClassName extends {$reflectionClass->getShortName()} implements MockedClass {
        private \$mockedClassConstructorParams;

        function __construct( MockedClassConstructorParams \$mockedClassConstructorParams ){
            \$this->mockedClassConstructorParams = \$mockedClassConstructorParams;
        }

        public function getInstanceReference(){
            return \$this->mockedClassConstructorParams->getInstanceReference();
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
    private function convertMethodListToCode( array $mockedMethodList ) {
        $code = '';
        foreach ( $mockedMethodList as $mockedMethod ) {
            $code .= <<<TXT

        {$mockedMethod->getVisibilityAsString()} function {$mockedMethod->getName()}( {$mockedMethod->getSignature()} ) {
            \$methodCall = new DebugBackTraceMethodCall( \$this, '{$mockedMethod->getName()}', func_get_args(), debug_backtrace() );
            return \$this->mockedClassConstructorParams->actionCall( \$methodCall );
        }

TXT;
        }

        return rtrim( trim( $code, ' ' ) );
    }


}