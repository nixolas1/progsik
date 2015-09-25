<?php

namespace tdt4237\webapp\validation;

use tdt4237\webapp\models\User;

class RegistrationFormValidation
{
    const MIN_USER_LENGTH = 3;
    
    private $validationErrors = [];
    
    public function __construct($username, $password)
    {
        return $this->validate($username, $password);
    }
    
    public function isGoodToGo()
    {
        return empty($this->validationErrors);
    }
    
    public function getValidationErrors()
    {
        return $this->validationErrors;
    }

    private function validate($username, $password)
    {
        if (empty($password)) {
            $this->validationErrors[] = 'Password cannot be empty';
        }

        if (strlen($username) < self::MIN_USER_LENGTH) {
            $this->validationErrors[] = "Username too short. Min length is " . self::MIN_USER_LENGTH;
        }

        if (preg_match('/^[A-Za-z0-9_]+$/', $username) === 0) {
            $this->validationErrors[] = 'Username can only contain letters and numbers';
        }
    }
}
