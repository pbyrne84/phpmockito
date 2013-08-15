<?php

namespace PHPMockito\Run;


use PHPMockito\Action\MethodCall;
use PHPMockito\Action\MethodCallActionInitialiser;

class Mockito {
    const CLASS_NAME = __CLASS__;


    /**
     * @param $className
     *
     * @return mixed
     */
    static public function mock( $className ) {
        $dependencyFactory = RuntimeState::getInstance()
                ->getDependencyFactory();

        $mockFactory = $dependencyFactory->getMockFactory();

        return $mockFactory->mock( $className );
    }


    /**
     * @param MethodCall $methodCall
     *
     * @return MethodCallActionInitialiser|null
     */
    static public function when( $methodCall ) {
        // return new MethodCallActionInitialiser( );
    }
}
