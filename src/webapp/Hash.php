<?php

namespace tdt4237\webapp;

use Symfony\Component\Config\Definition\Exception\Exception;

class Hash
{

    //static $salt = "1234";


    public function __construct()
    {
    }

    public static function make($plaintext)
    {
        //return hash('sha1', $plaintext . Hash::$salt);
        return password_hash($plaintext, PASSWORD_DEFAULT); //uses the bcrypt algorithm, creates a random salt

    }

    public function check($plaintext, $hash)
    {
        //return $this->make($plaintext) === $hash;
        return password_verify($plaintext, $hash);
    }

}
