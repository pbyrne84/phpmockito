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
    use \PHPMockito\Action\MethodCall;
    use \PHPMockito\Action\MethodCallListener;

    class $mockShortClassName extends {$reflectionClass->getShortName()} implements MockedClass {
        private \$methodCallListener;

        function __construct( MethodCallListener \$methodCallListener ){
            \$this->methodCallListener = \$methodCallListener;
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
            \$methodCall = new MethodCall( \$this, '{$mockedMethod->getName()}', func_get_args() );
            return \$this->methodCallListener->actionCall( \$methodCall );
        }


TXT;
        }

        return rtrim( $code );
    }


}
