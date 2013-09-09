<?php
namespace PHPMockito\Mock;

use PHPMockito\Action\DebugBackTraceMethodCall;
use PHPMockito\Action\MethodCallListener;

class MockedClassConstructorParams {
    const CLASS_NAME = __CLASS__;

    /** @var string */
    private $instanceReference;

    /** @var \PHPMockito\Action\MethodCallListener */
    private $methodCallListener;


    /**
     * @param string             $instanceReference
     * @param MethodCallListener $methodCallListener
     */
    function __construct( $instanceReference, MethodCallListener $methodCallListener ) {
        $this->instanceReference  = $instanceReference;
        $this->methodCallListener = $methodCallListener;
    }


    /**
     * @return string
     */
    public function getInstanceReference() {
        return $this->instanceReference;
    }


    /**
     * @param DebugBackTraceMethodCall $methodCall
     *
     * @return mixed
     */
    public function actionCall( DebugBackTraceMethodCall $methodCall ) {
        return $this->methodCallListener->actionCall( $methodCall );
    }


    /**
     * @param DebugBackTraceMethodCall $methodCall
     */
    public function registerCall( DebugBackTraceMethodCall $methodCall ) {
         $this->methodCallListener->registerLastCall( $methodCall );
    }


    public function hasSpyCall(  DebugBackTraceMethodCall $methodCall ){

    }




}
