<?php
spl_autoload_register( function ( $className ) {
    $potentialPath = __DIR__ . '/' . str_replace(  '\\' , '/', $className ) . '.php';
    if ( is_file( $potentialPath ) ) {
        require_once $potentialPath;
    }
} );


require_once __DIR__ . '/../lib/autoload.php';