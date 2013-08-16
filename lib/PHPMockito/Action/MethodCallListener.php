<?php

namespace PHPMockito\Action;

use PHPMockito\Expectancy\InitialisationCallListenerFactory;
use PHPMockito\Expectancy\InitialisationCallRegistrar;

class MethodCallListener {
    const CLASS_NAME = __CLASS__;

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
        $initialisationCallListener = $this->initialisationCallListenerFactory->createInitialisationCallListener();
        if ( $initialisationCallListener->tryInitialisationRegistration( $methodCall ) ) {
            return $methodCall->castToMethodCall();
        }

        return $this->initialisationCallRegistrar->retrieveMockMethodAction( $methodCall );
    }
}