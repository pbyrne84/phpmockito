<?php

namespace PHPMockito\Mock\Method;


use PHPMockito\Mock\MockedMethod;

class SpyMethodCodeGenerator implements MethodCodeGenerator {
    const CLASS_NAME = __CLASS__;


    /**
     * @param MockedMethod $mockedMethod
     *
     * @return string
     */
    public function generateMethodCode( MockedMethod $mockedMethod) {
        return  <<<TXT

        {$mockedMethod->getVisibilityAsString()} function {$mockedMethod->getName()}( {$mockedMethod->getSignature()} ) {
            \$arguments =  {$mockedMethod->getParameterArrayEntrapment()};
            \$methodCall = new DebugBackTraceMethodCall(
                \$this->mockedClassConstructorParams->getToStringAdaptorFactory(),
                \$this,
                '{$mockedMethod->getName()}',
                \$arguments,
                debug_backtrace()
            );

            if ( \$this->mockedClassConstructorParams->returnSpyParentMethodCall( \$methodCall ) ) {
                return parent::{$mockedMethod->getName()}( {$mockedMethod->getCommaSeparatedArguments() } );
            }

            \$this->mockedClassConstructorParams->registerCall( \$methodCall );
            return \$this->mockedClassConstructorParams->actionCall( \$methodCall );
        }

TXT;
    }
}
 