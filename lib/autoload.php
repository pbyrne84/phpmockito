<?php
require_once 'PHPMockito/phpmockwrapperfunctions.php';

spl_autoload_register( function ( $className ) {
    $classPath = __DIR__ . '/' . $className . '.php';
    if( !include_once $classPath ){
        throw new RuntimeException("Could not include " . $classPath );
    }
} );