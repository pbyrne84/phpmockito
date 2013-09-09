<?php

namespace PHPMockito\Mock\Method;


use PHPMockito\Mock\MockedMethod;

interface  MethodCodeGenerator {


    /**
     * @param MockedMethod $mockedMethod
     *
     * @return string
     */
    public function generateMethodCode( MockedMethod $mockedMethod );
}
 