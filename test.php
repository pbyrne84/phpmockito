<?php

/**
 * Class UserController
 * @method search
 * @property \UserSearchPlugin search
 */
class UserController{
    private $magicStuff;

    function __set( $name, $value ) {
        $this->magicStuff[ $name ] = $value;;
    }


    function __call( $name, $arguments ) {
        return call_user_func_array( $this->magicStuff[$name], $arguments );
    }


    public function add(){
        echo "Creating User...<br>";
    }

    public function edit(){
        echo "Updating User...<br>";
    }

    public function delete(){
        echo "Removing User...<br>";
    }

}


class UserSearchPlugin{

    public function __invoke(){
        echo "Searching User....<br>";
        var_dump( func_get_args() );
    }
}




$controller = new UserController;
$controller->search = new UserSearchPlugin();
$controller->search("MOO");