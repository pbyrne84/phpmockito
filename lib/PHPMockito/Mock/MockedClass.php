<?php

namespace PHPMockito\Mock;


interface MockedClass {
    const INTERFACE_MockedClass = __CLASS__;

    /**
     * @return string
     */
    public function getInstanceReference();
}
