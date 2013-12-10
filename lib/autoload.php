<?php
require_once 'PHPMockito/phpmockwrapperfunctions.php';

set_include_path( get_include_path() . PATH_SEPARATOR . __DIR__ );

spl_autoload_register( function ( $className ) {
    if ( false === strpos(  $className, 'PHPMockito\\' )) {
        return;
    }

    $className = str_replace( '\\', '/', $className );
    $classPath = __DIR__ . '/' . $className . '.php';
    if( !include_once $classPath ){
        throw new RuntimeException("Could not include " . $classPath );
    }
} );