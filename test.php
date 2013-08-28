<?php

$a = new Exception( "MOO" );
$b = new Exception( "MOO" );


var_dump( $a == $b );


class Moo{
    private    $a;
    private    $b;
    private    $c = 'banana';


    function __construct(  ) {
        $this->a = new DOMDocument();
        $this->b = new DOMDocument();
    }


}

$moo = new Moo();
var_dump( (array)$moo );