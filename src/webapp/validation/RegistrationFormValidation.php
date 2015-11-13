<?php

namespace tdt4237\webapp\validation;

use tdt4237\webapp\models\User;
use tdt4237\webapp\repository\UserRepository;

class RegistrationFormValidation
{
    const MIN_USER_LENGTH = 3;
    
    private $validationErrors = [];
    
    public function __construct($username, $password, $fullname, $address, $postcode)
    {
        $this->app = \Slim\Slim::getInstance();
        $this->userRepository = $this->app->userRepository;

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
            $this->validationErrors[] = 'Password can\'t be shorter than 8 characters';
        }//check if the pass is less than 8
        elseif(strlen($password) < 8) {
            $this->validationErrors[] = 'Password can\'t be shorter than 8 characters';
        }// check if the pass is greater than 1024
        elseif(strlen($password)> 1024){
            $this->validationErrors[] = 'Password too long';
        }

        //check if fullname is not empty
        if(empty($fullname)){
            $this->validationErrors[] = "Full name cannot be empty!";
        }
       
        if (empty($address)) {
            $this->validationErrors[] = "Address cannot be empty!";
        }

        if(empty($postcode)) {
            $this->validationErrors[] = "Please write in your post code";
        }
        elseif (strlen($postcode) != "4") {
            $this->validationErrors[] = "Post code must be exactly four digits";
        }//check if the postcode is in numbers
        elseif(preg_match('/^\d+$/',$postcode) === 0){
            $this->validationErrors[] = "Postcode must be in numbers";
        }

        if (preg_match('/^[A-Za-z0-9]+$/', $username) === 0) {
            $this->validationErrors[] = 'Username can only contain letters and numbers';
        }
        elseif (strlen($username) < 3) {
            $this->validationErrors[] = 'Username must be longer than 2';
        }

        elseif (strlen($username) > 32) {
            $this->validationErrors[] = 'Username must be shorter than 32';
        }

        elseif ($this->userRepository->checkUsernameAvailable($username) > 0) {
            $this->validationErrors[] = 'Username already exists';
        }
       
    }
}











