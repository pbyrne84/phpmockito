<?php
require_once 'PHPMockito/phpmockwrapperfunctions.php';

spl_autoload_register( function ( $className ) {
    require_once __DIR__ . '/' . $className . '.php';
} );