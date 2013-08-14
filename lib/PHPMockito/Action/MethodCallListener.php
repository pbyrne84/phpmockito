<?php

namespace PHPMockito\Action;

use PHPMockito\Expectancy\InitialisationCallListenerFactory;

class MethodCallListener {
    const CLASS_NAME = __CLASS__;

    /** @var InitialisationCallListenerFactory */
    private $initialisationCallListenerFactory;


    function __construct( InitialisationCallListenerFactory $initialisationCallListenerFactory ) {
        $this->initialisationCallListenerFactory = $initialisationCallListenerFactory;
    }


    /**
     * @param MethodCall $methodCall
     * @return mixed
     */
    public function actionCall( MethodCall $methodCall ){
        $initialisationCallListener = $this->initialisationCallListenerFactory->createInitialisationCallListener();
        if ( $initialisationCallListener->tryInitialisationRegistration()) {
        }
    }
}
