<?php

namespace PHPMockito\Action;

use PHPMockito\Expectancy\InitialisationCallListenerFactory;
use PHPMockito\Expectancy\InitialisationCallRegistrar;

class MethodCallListener {
    
    /** @var InitialisationCallListenerFactory */
    private $initialisationCallListenerFactory;

    /** @var \PHPMockito\Expectancy\InitialisationCallRegistrar */
    private $initialisationCallRegistrar;


    function __construct( InitialisationCallListenerFactory $initialisationCallListenerFactory,
                          InitialisationCallRegistrar $initialisationCallRegistrar ) {
        $this->initialisationCallListenerFactory = $initialisationCallListenerFactory;
        $this->initialisationCallRegistrar       = $initialisationCallRegistrar;
    }


    /**
     * @param DebugBackTraceMethodCall $methodCall
     *
     * @return mixed
     */
    public function actionCall( DebugBackTraceMethodCall $methodCall ) {
        return $this->initialisationCallRegistrar->retrieveMockMethodAction( $methodCall );
    }


    /**
     * @param DebugBackTraceMethodCall $methodCall
     */
    public function registerLastCall(  DebugBackTraceMethodCall $methodCall ) {
        $initialisationCallListener = $this->initialisationCallListenerFactory->createTestCaseCallVerifier();
        if ( $initialisationCallListener->callIsInTestCase( $methodCall ) ) {
            $this->initialisationCallRegistrar->registerLatestInitialisationSignature( $methodCall );
        }
    }


    /**
     * @param CallableMethod $methodCall
     *
     * @return bool
     */
    public function hasSpyCall( CallableMethod $methodCall ) {
        return $this->initialisationCallRegistrar->hasMockMethodAction( $methodCall );
    }
}
