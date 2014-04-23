<?php

namespace PHPMockito\Verify;


use PHPMockito\Action\CallableMethod;

class MethodCallLoggingStatus {
    const CLASS_NAME = __CLASS__;

    /** @var \PHPMockito\Action\CallableMethod */
    private $methodCall;

    /** @var int */
    private $count;

    /** @var string */
    private $message;


    /**
     * @param CallableMethod $methodCall
     * @param int        $count
     * @param string     $message
     */
    function __construct( CallableMethod $methodCall, $count, $message ) {
        $this->methodCall = $methodCall;
        $this->count = $count;
        $this->message = $message;
    }


    /**
     * @return int
     */
    public function getCount() {
        return $this->count;
    }


    /**
     * @return string
     */
    public function getAllCallsMessage() {
        return $this->message;
    }


    /**
     * @return \PHPMockito\Action\CallableMethod
     */
    public function getMethodCall() {
        return $this->methodCall;
    }


}
 