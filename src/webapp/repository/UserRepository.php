<?php

namespace tdt4237\webapp\repository;

use PDO;
use tdt4237\webapp\models\Age;
use tdt4237\webapp\models\Email;
use tdt4237\webapp\models\NullUser;
use tdt4237\webapp\models\User;
use tdt4237\webapp\models\BankCard;

class UserRepository
{
    const INSERT_QUERY   = "INSERT INTO users(user, pass, email, age, bio, role, fullname, address, postcode, bankcard) VALUES(:username, :password, :email , :age , :bio, :role, :fullname, :address, :postcode, :bankcard)";
    const UPDATE_QUERY   = "UPDATE users SET email= :email, age= :age, bio= :bio, role= :role, fullname = :fullname, address = :address, postcode = :postcode, bankcard =  :bankcard WHERE id= :id";
    const UPDATE_ROLE_QUERY = "UPDATE users SET role= :role WHERE user= :user";
    const FIND_BY_NAME   = "SELECT * FROM users WHERE user= :username";
    const DELETE_BY_NAME = "DELETE FROM users WHERE user= :username";
    const SELECT_ALL     = "SELECT * FROM users";
    const FIND_FULL_NAME   = "SELECT * FROM users WHERE user = :username";
    const FIND_BANKCARD   = "SELECT bankcard FROM users WHERE user = :username";
    const FIND_BALANCE   = "SELECT balance FROM users WHERE user = :username";
    const UPDATE_BALANCE  = "UPDATE users SET balance= :balance WHERE user = :username";

    /**
     * @var PDO
     */
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /*
    
    NU JÄVLAR VAD RENT DET BLEV !

    #NYVASKET

    */

    public function makeUserFromRow(array $row)
    {
        $user = new User($row['user'], $row['pass'], $row['fullname'], $row['address'], $row['postcode']);
        $user->setUserId($row['id']);
        $user->setFullname($row['fullname']);
        $user->setAddress(($row['address']));
        $user->setPostcode((($row['postcode'])));
        $user->setBio($row['bio']);
        $user->setBalance($row['balance']);
        $user->setIsAdmin($row['role']);

        if (!empty($row['email'])) {
            $user->setEmail(new Email($row['email']));
        }

        if (!empty($row['age'])) {
            $user->setAge(new Age($row['age']));
        }

        if (!empty($row['bankcard'])) {
            $user->setBankCard(new BankCard($row['bankcard']));
        }
        return $user;
    }


    // VASKAD
    public function getNameByUsername($username)
    {
        $query = self::FIND_FULL_NAME;
        $query_params = array( ':username' => $username); 
        try { 
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($query_params); 
            // Tvilsom linje under 
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        } 
        catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); } 


        return $row['fullname'];

    }


    // VASKAD
    public function findByUser($username)
    {   

        $query = self::FIND_BY_NAME;
        $query_params = array( ':username' => $username); 

        try { 
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($query_params); 
            // Tvilsom linje under 
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result === false) {
                return false;
            }

            return $this->makeUserFromRow($result);
        } 
        catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); } 
        /*

        $query  = sprintf(self::FIND_BY_NAME, $username);
        $result = $this->pdo->query($query, PDO::FETCH_ASSOC);
        $row = $result->fetch();
        
        if ($row === false) {
            return false;
        }


        return $this->makeUserFromRow($row);
        */
    }

    public function checkUsernameAvailable($username){
        $query = self::FIND_BY_NAME;
        $query_params = array( ':username' => $username); 

        try { 
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($query_params); 
            $number_of_rows = $stmt->fetchColumn();
            return $number_of_rows;
        } 
        catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); } 

    }

    public function checkBankCard($username){
        $query = self::FIND_BANKCARD;
        $query_params = array( ':username' => $username); 

        try { 
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($query_params); 
            $res = $stmt->fetchColumn();
            if ($res == null){
                return false;
            }
            return true;

        } 
        catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); } 

    }
    public function findBalance($username){
        $query = self::FIND_BALANCE;
        $query_params = array( ':username' => $username); 

        try { 
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($query_params); 
            return $stmt->fetchColumn();
            
        } 
        catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); } 

    }
    public function updateBalance($username, $price){
        $query = self::UPDATE_BALANCE;
        $currentBalance = $this->findBalance($username);
        $newBalance = $currentBalance + $price;
        $query_params = array( ':balance' => $newBalance ,':username' => $username); 

        try { 
            $stmt = $this->pdo->prepare($query);
            
            return $stmt->execute($query_params); 
            
        } 
        catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); } 

    }




    /*

    Får feilmelding i applikasjon, men alt fungerer tilsynelatende :)

    */
    public function deleteByUsername($username)
    {
        $query = self::DELETE_BY_NAME;
        $query_params = array( ':username' => $username); 
        try { 
            $stmt = $this->pdo->prepare($query);
            $result = $stmt->execute($query_params); 

            if ( $result ) {
                return 1;
            }
            
        } 
        catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); } 


        /*
        return $this->pdo->exec(
            sprintf(self::DELETE_BY_NAME, $username)
        );
        */
    }

    public function setDocByUsername($username)
    {
        $query = self::UPDATE_ROLE_QUERY;
        $query_params = array(':role' => 2, ':user' => $username); 
        try { 
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($query_params); 
            return 1;
        } 
        catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); } 
    }

    public function removeDocByUsername($username)
    {
        $query = self::UPDATE_ROLE_QUERY;
        $query_params = array(':role' => 0, ':user' => $username); 
        try { 
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($query_params); 
            return 1;
        } 
        catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); } 
    }



    public function all()
    {
        $rows = $this->pdo->query(self::SELECT_ALL);
        
        if ($rows === false) {
            return [];
            throw new \Exception('PDO error in all()');
        }

        return array_map([$this, 'makeUserFromRow'], $rows->fetchAll());
    }

    public function save(User $user)
    {
        if ($user->getUserId() === null) {
            return $this->saveNewUser($user);
        }

        $this->saveExistingUser($user);
    }


    // VASKAD
    public function saveNewUser(User $user)
    {
        $query = self::INSERT_QUERY;
        $query_params = array( ':username' => $user->getUsername(), ':password' => $user->getHash(), ':email' => $user->getEmail(), ':age' => $user->getAge(), ':bio' => $user->getBio(), ':role' => $user->isAdmin(), ':fullname' => $user->getFullname(), ':address' => $user->getAddress(), ':postcode' => $user->getPostcode(), ':bankcard' => $user->getBankCard()); 

        try { 
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($query_params); 
            return 1;
        } 
        catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); } 
     
    }


    // VASKAD
    public function saveExistingUser(User $user)
    {
        $query = self::UPDATE_QUERY;
        $query_params = array(':email' => $user->getEmail(), ':age' => $user->getAge(), ':bio' => $user->getBio(), ':role' => $user->isAdmin(), ':fullname' => $user->getFullname(), ':address' => $user->getAddress(), ':postcode' => $user->getPostcode(), ':id' => $user->getUserId(), ':bankcard' => $user->getBankCard()); 
        try { 
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($query_params); 
            return 1;
        } 
        catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); } 

     
    }

}
