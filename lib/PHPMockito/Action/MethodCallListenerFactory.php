<?php

namespace PHPMockito\Action;


interface MethodCallListenerFactory {
    const INTERFACE_MethodCallListenerFactory = __CLASS__;


    /**
     * @return MethodCallListener
     */
    public function createMethodCallListener();
}
 