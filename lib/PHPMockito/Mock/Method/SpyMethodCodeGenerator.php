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
            \$methodCall = new DebugBackTraceMethodCall(
                \$this, '{$mockedMethod->getName()}', {$mockedMethod->getParameterArrayEntrapment()}, debug_backtrace()
            );

            \$this->mockedClassConstructorParams->registerCall( \$methodCall );
            return \$this->mockedClassConstructorParams->actionCall( \$methodCall );
        }

TXT;
    }
}
 