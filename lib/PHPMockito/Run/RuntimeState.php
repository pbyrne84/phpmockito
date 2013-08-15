<?php

namespace PHPMockito\Run;


class RuntimeState {
    const CLASS_NAME = __CLASS__;

    /** @var RuntimeState  */
    private static $instance;

    /** @var \PHPMockito\Run\DependencyFactory  */
    private $dependencyFactory;


    function __construct() {
        $this->dependencyFactory = new DependencyFactory();
    }


    /**
     * @return RuntimeState
     */
    public static function getInstance(){
        if( !isset( self::$instance) ){
             self::$instance = new RuntimeState();
        }

        return self::$instance;
    }


    /**
     * @return \PHPMockito\Run\DependencyFactory
     */
    public function getDependencyFactory() {
        return $this->dependencyFactory;
    }

}
 