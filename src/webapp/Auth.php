<?php

namespace tdt4237\webapp;

use Exception;
use tdt4237\webapp\Hash;
use tdt4237\webapp\repository\UserRepository;

class Auth
{

    /**
     * @var Hash
     */
    private $hash;

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository, Hash $hash)
    {
        $this->userRepository = $userRepository;
        $this->hash           = $hash;
    }

    public function checkCredentials($username, $password)
    {
        $user = $this->userRepository->findByUser($username);

        if ($user === false) {
            return false;
        }

        return $this->hash->check($password, $user->getHash());
    }

    /**
     * Check if is logged in.
     */
    public function check()
    {
        return isset($_SESSION['user']);
    }

    public function getUsername() {
        if(isset($_SESSION['user'])){
        return $_SESSION['user'];
        }
    }

    /**
     * Check if the person is a guest.
     */
    public function guest()
    {
        return $this->check() === false;
    }

    /**
     * Get currently logged in user.
     */
    public function user()
    {
        if ($this->check()) {
            return $this->userRepository->findByUser($_SESSION['user']);
        }

        throw new Exception('Not logged in but called Auth::user() anyway');
    }

    /**
     * Is currently logged in user admin?
     */
    public function isAdmin()
    {
        if ($this->check()) {
            $user = $this->user();
            if ($user != false) {
                return $user->isAdmin();
            }
            return false;
        }

        throw new Exception('Not logged in but called Auth::isAdmin() anyway');
    }
    
    public function isPaying()
    {
        if ($this->check()) {
            $user = $this->user();
            if ($user != false) {
                return $user->isPaying();
            }
            return false;
        }
    }

    public function isDoctor()
    {
        if ($this->check()) {
            $user = $this->user();
            if ($user != false) {
                return $user->isDoctor();
            }
            return false;
        }
    }

    public function logout()
    {
        session_destroy();
    }

}
