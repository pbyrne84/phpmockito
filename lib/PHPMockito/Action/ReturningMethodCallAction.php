<?php

namespace PHPMockito\Action;


class ReturningMethodCallAction implements MethodCallAction{
    const CLASS_NAME = __CLASS__;

    /** @var mixed */
    private $returnValue;


    /**
     * @param mixed $returnValue
     */
    function __construct( $returnValue ) {
        $this->returnValue = $returnValue;
    }


    /**
     * @return mixed
     */
    public function getReturnValue() {
        return $this->returnValue;
    }
}
