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
        if ( $mockedMethod->getName() == '__sleep' ) {
            $body = <<<TXT
return array( 'mockedClassConstructorParams', 'defaultValueMap' );
TXT;
        }else{
            $body = <<<TXT
\$methodCall = new DebugBackTraceMethodCall(
                \$this->mockedClassConstructorParams->getToStringAdaptorFactory(),
                \$this,
                '{$mockedMethod->getName()}',
                {$mockedMethod->getParameterArrayEntrapment()},
                debug_backtrace()
            );

            \$this->mockedClassConstructorParams->registerCall( \$methodCall );
            \$actionedCall = \$this->mockedClassConstructorParams->actionCall( \$methodCall );

            return \$actionedCall;
TXT;
        }

        $returnsReferenceSignature = $mockedMethod->getReturnsReferenceSignature();
        return <<<TXT

        {$mockedMethod->getVisibilityAsString()} function {$returnsReferenceSignature}{$mockedMethod->getName()}( {$mockedMethod->getSignature()} ) {
            $body
        }

TXT;
    }
}
 