<?php

namespace PHPMockito\Expectancy;


use PHPMockito\Action\MethodCallAction;

class MatchedAction {
    
    /** @var \PHPMockito\Action\MethodCallAction */
    private $action;

    /** @var string */
    private $methodCallToMapKey;

    /** @var int */
    private $index;


    /**
     * @param MethodCallAction $action
     * @param string           $methodCallToMapKey
     * @param int              $index
     */
    function __construct( MethodCallAction $action, $methodCallToMapKey, $index ) {
        $this->action = $action;
        $this->methodCallToMapKey = $methodCallToMapKey;
        $this->index = $index;
    }


    /**
     * @return MethodCallAction
     */
    public function getAction() {
        return $this->action;
    }


    /**
     * @return int
     */
    public function getIndex() {
        return $this->index;
    }


    /**
     * @return string
     */
    public function getMethodCallToMapKey() {
        return $this->methodCallToMapKey;
    }


} 