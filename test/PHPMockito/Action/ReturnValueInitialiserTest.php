<?php
namespace PHPMockito\Action;

use PHPMockito\Expectancy\InitialisationCallRegistrar;

class ReturnValueInitialiserTest extends \PHPUnit_Framework_TestCase {

    const CLASS_NAME = __CLASS__;


    protected function setUp() {
    }


    public function test_thenThrow_passedExceptionInstanceIsSet() {
        /** @var $initialisationCallRegistrar InitialisationCallRegistrar */
        $initialisationCallRegistrar = mock( InitialisationCallRegistrar::INTERFACE_InitalisationCallRegistrar );
        $methodCall                  = mock( ExpectedMethodCall::CLASS_NAME );

        $returnValueInitialiser      = new MethodCallActionInitialiser(
            $initialisationCallRegistrar,
            $methodCall
        );

        $exception = new \Exception();

        $returnValueInitialiser->thenThrow( $exception );
        $methodCallAction               = new ExceptionMethodCallAction( $exception );
        $expectedFullActionedMethodCall = new FullyActionedMethodCall( $methodCall, $methodCallAction );

        verify( $initialisationCallRegistrar )->registerMockMethodExpectancy( $expectedFullActionedMethodCall );
    }


    public function test_thenThrow_passedStringIsConvertedToExceptionAndSet() {
        /** @var $initialisationCallRegistrar InitialisationCallRegistrar */
        $initialisationCallRegistrar = mock( InitialisationCallRegistrar::INTERFACE_InitalisationCallRegistrar );
        $methodCall                  = mock( ExpectedMethodCall::CLASS_NAME );

        $returnValueInitialiser      = new MethodCallActionInitialiser(
            $initialisationCallRegistrar,
            $methodCall
        );

        $returnValueInitialiser->thenThrow( '\InvalidArgumentException' );

        $exception = new \InvalidArgumentException();
        $methodCallAction               = new ExceptionMethodCallAction( $exception );
        $expectedFullActionedMethodCall = new FullyActionedMethodCall( $methodCall, $methodCallAction );

        verify( $initialisationCallRegistrar )->registerMockMethodExpectancy( $expectedFullActionedMethodCall );
    }



    public function test_thenReturn(){
        /** @var $initialisationCallRegistrar InitialisationCallRegistrar */
        $initialisationCallRegistrar = mock( InitialisationCallRegistrar::INTERFACE_InitalisationCallRegistrar );
        $methodCall                  = mock( ExpectedMethodCall::CLASS_NAME );

        $returnValueInitialiser      = new MethodCallActionInitialiser(
            $initialisationCallRegistrar,
            $methodCall
        );

        $value = new \DOMDocument();
        $value->loadXML("<xml/>");

        $returnValueInitialiser->thenReturn( $value );

        $methodCallAction               = new ReturningMethodCallAction( $value );
        $expectedFullActionedMethodCall = new FullyActionedMethodCall( $methodCall, $methodCallAction );

        verify( $initialisationCallRegistrar )->registerMockMethodExpectancy( $expectedFullActionedMethodCall );
    }
}
  
