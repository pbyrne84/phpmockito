<?php

namespace PHPMockito\Action;


class ExceptionMethodCallAction implements MethodCallAction{
    const CLASS_NAME = __CLASS__;

    /** @var \Exception */
    private $exceptionToBeThrown;


    function __construct( \Exception $exceptionToBeThrown ) {
        $this->exceptionToBeThrown = $exceptionToBeThrown;
    }


    /**
     * @return \Exception
     */
    public function getExceptionToBeThrown() {
        return $this->exceptionToBeThrown;
    }


}
