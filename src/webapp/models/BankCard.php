<?php

namespace tdt4237\webapp\models;

class BankCard
{

    private $bankcard;
    
    public function __construct($bankcard)
    {
        if (! preg_match('/^\d{10}$/', $bankcard)) {
          throw new \Exception("Banknumber must be 10...");
        }
        
        $this->bankcard = $bankcard;
    }

    public function __toString()
    {
        return $this->bankcard;
    }

}


