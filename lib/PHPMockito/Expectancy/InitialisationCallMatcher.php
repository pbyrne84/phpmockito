<?php

namespace PHPMockito\Expectancy;


interface InitialisationCallMatcher {

    public function checkIsInitialisationCall( array $debugBackTrace );

}
