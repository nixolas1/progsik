<?php

namespace tdt4237\webapp\validation;

class EditUserFormValidation
{
    private $validationErrors = [];
    
    public function __construct($email, $bio, $age, $banknumber)
    {
        $this->validate($email, $bio, $age, $banknumber);
    }
    
    public function isGoodToGo()
    {
        return \count($this->validationErrors) === 0;
    }
    
    public function getValidationErrors()
    {
        return $this->validationErrors;
    }

    private function validate($email, $bio, $age, $banknumber)
    {
        $this->validateEmail($email);
        $this->validateAge($age);
        $this->validateBio($bio);
        $this->validateBanknumber($banknumber);
    }
    
    private function validateEmail($email)
    {
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->validationErrors[] = "Invalid email format on email";
        }
    }
    
    private function validateAge($age)
    {
        if (! is_numeric($age) or $age < 0 or $age > 130) {
            $this->validationErrors[] = 'Age must be between 0 and 130.';
        }
    }

    private function validateBanknumber($bank)
    {
        if (!empty($bank) && strlen($bank) < 11) {
            $this->validationErrors[] = 'Bank number must be at least 11 numbers.';
        }
    }

    private function validateBio($bio)
    {
        if (empty($bio)) {
            $this->validationErrors[] = 'Bio cannot be empty';
        }
    }
}
