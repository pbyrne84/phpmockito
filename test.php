<?php
class Db{
    function getUsers(){


    }
}


$db = new Db();
function a(){
    global $db;
    $db->getUsers();
    $db = null;
}


function b(){
    global $db;
    $db->getUsers(); //fatal where has the db gone in the call chain?
}


a();
b();