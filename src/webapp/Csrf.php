<?php

namespace tdt4237\webapp;

class Csrf
{

    /*
     * Token should be set in $_SESSION['CSRF_token']
     */

    // Public methods

    public function __construct() {
        if ( !$this->tokenIsset() ) {
            $_SESSION['CSRF_token'] = $this->generateToken();
        }
    }

    public function getToken() {
        return $_SESSION['CSRF_token'];
    }

    public function validate($candidate) {
        return $_SESSION['CSRF_token'] == $candidate;
    }

    // Private methods

    private function generateToken() {
        $datetime = date("Y-m-d H:i:s");
        $iterations = rand(5, 15);
        $random_number = rand();

        $temp = hash("sha256", $datetime . strval($random_number));

        for ($i = 0; $i < $iterations; $i++)
        {
            $temp = hash("sha256", $temp);
        }

        return $temp;
    }

    private function tokenIsset() {
        return isset($_SESSION['CSRF_token']) == true;
    }

}
