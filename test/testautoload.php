<?php

spl_autoload_register( function ( $className ) {
    if ( is_file( __DIR__ . '/' . $className . '.php' ) ) {
        require_once __DIR__ . '/' . $className . '.php';
    }
} );

require_once __DIR__ . '/../lib/autoload.php';