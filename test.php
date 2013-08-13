<?php

$test = 'array (

)';


preg_match( '~^(array.*[ \r\n].*)~m', $test, $matches );

var_dump( $matches );