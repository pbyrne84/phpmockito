<?php

namespace PHPMockito\Action;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\TestCase;
use PHPMockito\Expectancy\InitialisationCallRegistrar;
use PHPMockito\ToString\ToStringAdaptorFactory;

class ReturnValueInitialiserTest extends TestCase {

    

    /**
     * Control for the others as they are success based
     * @throws AssertionFailedError
     * @return void
     */
    public function test_fails_when_verify_fails_with_wrong_count() {
        /** @var $initialisationCallRegistrar InitialisationCallRegistrar */
        $initialisationCallRegistrar = mock( InitialisationCallRegistrar::INTERFACE_InitalisationCallRegistrar );
        $methodCall                  = mock( ExpectedMethodCall::class );
        $toStringAdaptorFactory      = mock( ToStringAdaptorFactory::class );

        $returnValueInitialiser = new MethodCallActionInitialiser(
                $toStringAdaptorFactory,
                $initialisationCallRegistrar,
                $methodCall
        );

        $exception = new \Exception();

        $returnValueInitialiser->thenThrow( $exception );
        $methodCallAction               = new ExceptionMethodCallAction( $exception );
        $expectedFullActionedMethodCall = new FullyActionedMethodCall(
                $toStringAdaptorFactory,
                $methodCall,
                $methodCallAction,
                $returnValueInitialiser
        );

        $this->expectException(AssertionFailedError::class);
        verify( $initialisationCallRegistrar,2 )->registerMockMethodExpectancy( $expectedFullActionedMethodCall );
    }


    public function test_thenThrow_passedExceptionInstanceIsSet() {
        /** @var $initialisationCallRegistrar InitialisationCallRegistrar */
        $initialisationCallRegistrar = mock( InitialisationCallRegistrar::INTERFACE_InitalisationCallRegistrar );
        $methodCall                  = mock( ExpectedMethodCall::class );
        $toStringAdaptorFactory      = mock( ToStringAdaptorFactory::class );

        $returnValueInitialiser = new MethodCallActionInitialiser(
                $toStringAdaptorFactory,
                $initialisationCallRegistrar,
                $methodCall
        );

        $exception = new \Exception();

        $returnValueInitialiser->thenThrow( $exception );
        $methodCallAction               = new ExceptionMethodCallAction( $exception );
        $expectedFullActionedMethodCall = new FullyActionedMethodCall(
                $toStringAdaptorFactory,
                $methodCall,
                $methodCallAction,
                $returnValueInitialiser
        );

        verify( $initialisationCallRegistrar )->registerMockMethodExpectancy( $expectedFullActionedMethodCall );
        //Hack as assertion has to be made
        $this->assertTrue(true);
    }


    public function test_thenThrow_passedStringIsConvertedToExceptionAndSet() {
        /** @var $initialisationCallRegistrar InitialisationCallRegistrar */
        $initialisationCallRegistrar = mock( InitialisationCallRegistrar::INTERFACE_InitalisationCallRegistrar );
        $methodCall                  = mock( ExpectedMethodCall::class );
        $toStringAdaptorFactory      = mock( ToStringAdaptorFactory::class );

        $returnValueInitialiser = new MethodCallActionInitialiser(
                $toStringAdaptorFactory,
                $initialisationCallRegistrar,
                $methodCall
        );

        $returnValueInitialiser->thenThrow( '\InvalidArgumentException' );

        $exception                      = new \InvalidArgumentException();
        $methodCallAction               = new ExceptionMethodCallAction( $exception );
        $expectedFullActionedMethodCall = new FullyActionedMethodCall(
                $toStringAdaptorFactory,
                $methodCall,
                $methodCallAction,
                $returnValueInitialiser
        );

        verify( $initialisationCallRegistrar )->registerMockMethodExpectancy( $expectedFullActionedMethodCall );
        //Hack as assertion has to be made
        $this->assertTrue(true);
    }


    public function test_thenReturn() {
        /** @var $initialisationCallRegistrar InitialisationCallRegistrar */
        $initialisationCallRegistrar = mock( InitialisationCallRegistrar::INTERFACE_InitalisationCallRegistrar );
        $methodCall                  = mock( ExpectedMethodCall::class );
        $toStringAdaptorFactory      = mock( ToStringAdaptorFactory::class );

        $returnValueInitialiser = new MethodCallActionInitialiser(
                $toStringAdaptorFactory,
                $initialisationCallRegistrar,
                $methodCall
        );

        $value = new \DOMDocument();
        $value->loadXML( "<xml/>" );

        $returnValueInitialiser->thenReturn( $value );

        $methodCallAction               = new ReturningMethodCallAction( $value );
        $expectedFullActionedMethodCall = new FullyActionedMethodCall(
                $toStringAdaptorFactory,
                $methodCall,
                $methodCallAction,
                $returnValueInitialiser
        );

        verify( $initialisationCallRegistrar )->registerMockMethodExpectancy( $expectedFullActionedMethodCall );
        //Hack as assertion has to be made
        $this->assertTrue(true);
    }
}
  
