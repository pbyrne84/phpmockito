<?php

namespace PHPMockito\Mock\Method;


use PHPMockito\Mock\MockedMethod;

class MockMethodCodeGenerator implements MethodCodeGenerator {
    const CLASS_NAME = __CLASS__;


    /**
     * @param MockedMethod $mockedMethod
     *
     * @return string
     */
    public function generateMethodCode( MockedMethod $mockedMethod) {
        return  <<<TXT

        {$mockedMethod->getVisibilityAsString()} function {$mockedMethod->getName()}( {$mockedMethod->getSignature()} ) {
            if( '{$mockedMethod->getName()}' == '__sleep' ){
                return array('mockedClassConstructorParams','defaultValueMap');
            }

            \$methodCall = new DebugBackTraceMethodCall(
                \$this->mockedClassConstructorParams->getToStringAdaptorFactory(),
                \$this,
                '{$mockedMethod->getName()}',
                {$mockedMethod->getParameterArrayEntrapment()},
                debug_backtrace()
            );

            \$this->mockedClassConstructorParams->registerCall( \$methodCall );
            return \$this->mockedClassConstructorParams->actionCall( \$methodCall );
        }

TXT;
    }
}
 