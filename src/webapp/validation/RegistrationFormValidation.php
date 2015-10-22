<?php

namespace tdt4237\webapp\validation;

use tdt4237\webapp\models\User;

class RegistrationFormValidation
{
    const MIN_USER_LENGTH = 3;
    
    private $validationErrors = [];
    
    public function __construct($username, $password, $fullname, $address, $postcode)
    {
        $this->app = \Slim\Slim::getInstance();
        return $this->validate($username, $password, $fullname, $address, $postcode);
    }
    
    public function isGoodToGo()
    {
        return empty($this->validationErrors);
    }
    
    public function getValidationErrors()
    {
        return $this->validationErrors;
    }

    private function validate($username, $password, $fullname, $address, $postcode)
    {
        if (empty($password)) {
            $this->validationErrors[] = 'Password cannot be empty';
        }
        
        if (strlen($username) > 20 or strlen($username) < 2) {
            $this->validationErrors[] = "Username must be between 3 and 20 characters";
        }
        
        if (strlen($password) > 72) {
            $this->validationErrors[] = "Password cannot contain more than 72 characters";
        }
        
        if (strlen($password) < 8) {
            $this->validationErrors[] = "Password should have atleast 8 characters";
        }

        if(empty($fullname)) {
            $this->validationErrors[] = "Please write in your full name";
        }

        if(empty($address)) {
            $this->validationErrors[] = "Please write in your address";
        }

        if(empty($postcode)) {
            $this->validationErrors[] = "Please write in your post code";
        }

        if (strlen($postcode) != "4") {
            $this->validationErrors[] = "Post code must be exactly four digits";
        }
        
        if (!ctype_digit($postcode)) {
            $this->validationErrors[] = "Post code must be a number";
        }

        if (preg_match('/^[A-Za-z0-9_]+$/', $username) === 0) {
            $this->validationErrors[] = 'Username can only contain letters and numbers';
        }
        
        if ($this->app->userRepository->findByUser($username)) {
            $this->validationErrors[] = 'Username already exist';
        }
    }
}
