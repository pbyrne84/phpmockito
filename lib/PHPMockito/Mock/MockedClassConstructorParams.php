<?php
namespace PHPMockito\Mock;

use PHPMockito\Action\DebugBackTraceMethodCall;
use PHPMockito\Action\MethodCallListener;
use PHPMockito\ToString\ToStringAdaptorFactory;

class MockedClassConstructorParams {
    const CLASS_NAME = __CLASS__;

    /** @var string */
    private $instanceReference;

    /** @var \PHPMockito\Action\MethodCallListener */
    private $methodCallListener;

    /** @var  ToStringAdaptorFactory */
    private $toStringAdaptorFactory;


    /**
     * @param ToStringAdaptorFactory $toStringAdaptorFactory
     * @param string                 $instanceReference
     * @param MethodCallListener     $methodCallListener
     */
    function __construct( ToStringAdaptorFactory $toStringAdaptorFactory,
                          $instanceReference,
                          MethodCallListener $methodCallListener ) {
        $this->instanceReference      = $instanceReference;
        $this->methodCallListener     = $methodCallListener;
        $this->toStringAdaptorFactory = $toStringAdaptorFactory;
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


    /**
     * @param DebugBackTraceMethodCall $methodCall
     *
     * @return bool
     */
    public function returnSpyParentMethodCall( DebugBackTraceMethodCall $methodCall ) {
        return !$this->methodCallListener->hasSpyCall( $methodCall );
    }


    /**
     * @return ToStringAdaptorFactory
     */
    public function getToStringAdaptorFactory() {
        return $this->toStringAdaptorFactory;
    }
}
