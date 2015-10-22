<?php

namespace tdt4237\webapp\models;

class User
{

    protected $userId       = null;
    protected $username;
    protected $fullname;
    protected $address;
    protected $postcode;
    protected $hash;
    protected $email        = null;
    protected $bio          = 'Bio is empty.';
    protected $age;
    protected $banknumber;
    protected $isAdmin      = 0;
    protected $isDoctor     = 0;
    protected $isPaying     = 0;
    protected $comanyEarned = 0;
    protected $earned       = 0;
    protected $spent        = 0;

    function __construct($username, $hash, $fullname, $address, $postcode)
    {
        $this->username = $username;
        $this->hash = $hash;
        $this->fullname = $fullname;
        $this->address = $address;
        $this->postcode = $postcode;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getHash()
    {
        return $this->hash;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getBio()
    {
        return $this->bio;
    }

    public function getAge()
    {
        return $this->age;
    }

    public function getBanknumber()
    {
        return $this->banknumber;
    }

    public function getFullname() {
        return $this->fullname;
    }

    public function setFullname($fullname) {
        $this->fullname = $fullname;
    }

    public function getAddress() {
        return $this->address;
    }

    public function setAddress($address) {
        $this->address = $address;
    }

    public function getPostcode() {
        return $this->postcode;

    }

    public function setPostcode($postcode) {
        $this->postcode = $postcode;

    }

    public function setBanknumber($banknumber) {
        $this->banknumber = $banknumber;

    }

    public function isAdmin()
    {
        return $this->isAdmin === '1';
    }
    
    public function isPaying()
    {
        return $this->isPaying === '1';
    }

    public function isDoctor()
    {
        return $this->isDoctor == '1';
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    public function setHash($hash)
    {
        $this->hash = $hash;
        return $this;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function setBio($bio)
    {
        $this->bio = $bio;
        return $this;
    }

    public function setAge($age)
    {
        $this->age = $age;
        return $this;
    }

    public function setIsAdmin($isAdmin)
    {
        $this->isAdmin = $isAdmin;
        return $this;
    }
    
    public function setIsPaying($isPaying)
    {
        $this->isPaying = $isPaying;
        return $this;
    }

    public function setIsDoctor($isDoctor)
    {
        $this->isDoctor = $isDoctor;
        return $this;
    }
    
    public function getEarned()
    {
        return $this->earned;
    }
    
    public function getSpent()
    {
        return $this->spent;
    }

    public function getCompanyEarned()
    {
        return $this->companyEarned;
    }
    
    public function setCompanyEarned($company)
    {
        $this->companyEarned = $company;
        return $this;
    }

    public function setEarned($earned)
    {
        $this->earned = $earned;
        return $this;
    }
    
    public function setSpent($spent)
    {
        $this->spent = $spent;
        return $this;
    }
}
