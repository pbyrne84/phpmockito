<?php

namespace PHPMockito\TestClass;


class SignatureTestClass {
    

    public function testMethodAllRequired( \DOMDocument $domDocument1,
                                           array $requiredArray,
                                           $nonTypeHint,
                                           \Closure $closure1 ) {

    }


    public function testMethodAllOptional( \DOMDocument $domDocument1 = null,
                                           array $requiredArray = array(),
                                           $nonTypeHint = '',
                                           \Closure $closure1 = null ) {

    }
}
