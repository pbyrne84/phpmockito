<?php

namespace PHPMockito\Action;


interface MethodCallAction {
    const INTERFACE_MethodCallAction = __CLASS__;


    /**
     * @param \Exception|string $exception
     *
     * @throws \InvalidArgumentException
     * @return \PHPMockito\Action\FullyActionedMethodCall
     */
    public function thenThrow( $exception );


    /**
     * @param mixed $value
     *
     * @return FullyActionedMethodCall
     */
    public function thenReturn( $value );
}
