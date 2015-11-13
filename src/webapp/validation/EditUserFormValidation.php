<?php

namespace tdt4237\webapp\validation;

class EditUserFormValidation
{
    private $validationErrors = [];
    
    public function __construct($email, $bio, $age, $bankcard)
    {
        $this->validate($email, $bio, $age, $bankcard);
    }
    
    public function isGoodToGo()
    {
        return \count($this->validationErrors) === 0;
    }
    
    public function getValidationErrors()
    {
        return $this->validationErrors;
    }

    private function validate($email, $bio, $age, $bankcard)
    {
        $this->validateEmail($email);
        $this->validateAge($age);
        $this->validateBio($bio);
        $this->validateBankCard($bankcard);
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

    private function validateBio($bio)
    {
        if (empty($bio) or strlen($bio) > 20000) {
            $this->validationErrors[] = 'Bio cannot be empty or be longer than 20000 characters';
        }
    }

    private function validateBankCard($bankcard)
    {
        if (preg_match('/^\d{10}$/', $bankcard) or strlen((string)$bankcard) == 0) {
          //
        }
        else {
            $this->validationErrors[] = 'Bankcard must contain 10 numbers only';
        }
    }
}
