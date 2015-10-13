<?php

namespace tdt4237\webapp\repository;

use PDO;
use tdt4237\webapp\models\Age;
use tdt4237\webapp\models\Email;
use tdt4237\webapp\models\NullUser;
use tdt4237\webapp\models\User;

class UserRepository
{
    const INSERT_QUERY   = "INSERT INTO users(user, pass, email, age, bio, isadmin, isdoctor, banknumber, fullname, address, postcode) VALUES(?, ?, ? , ? , ?, ?, ?, ?, ?, ?, ?)";
    const UPDATE_QUERY   = "UPDATE users SET email=?, age=?, bio=?, isadmin=?, isdoctor=?, banknumber=?, fullname =?, address = ?, postcode = ? WHERE id=?";
    const FIND_BY_NAME   = "SELECT * FROM users WHERE user=?";
    const DELETE_BY_NAME = "DELETE FROM users WHERE user=?";
    const SELECT_ALL     = "SELECT * FROM users";
    const FIND_FULL_NAME   = "SELECT * FROM users WHERE user=?";

    /**
     * @var PDO
     */
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function makeUserFromRow(array $row)
    {
        $user = new User($row['user'], $row['pass'], $row['fullname'], $row['address'], $row['postcode']);
        $user->setUserId($row['id']);
        $user->setFullname($row['fullname']);
        $user->setAddress(($row['address']));
        $user->setPostcode((($row['postcode'])));
        $user->setBio($row['bio']);
        $user->setIsAdmin($row['isadmin']);
        $user->setIsDoctor($row['isdoctor']);
        $user->setBanknumber($row['banknumber']);

        if (!empty($row['email'])) {
            $user->setEmail(new Email($row['email']));
        }

        if (!empty($row['age'])) {
            $user->setAge(new Age($row['age']));
        }
        
        if (!empty($row['banknumber'])) {
            $user->setIsPaying(1);
        }

        return $user;
    }

    public function getNameByUsername($username)
    {
        $query = $this->db->prepare(self::FIND_FULL_NAME);
        $query->execute(array($username));
        //$result = $this->db->query($query, PDO::FETCH_ASSOC);
        $row = $query->fetch();
        return $row['fullname'];

    }

    public function findByUser($username)
    {
        $query  = $this->db->prepare(self::FIND_BY_NAME);

        $query->execute(array($username));
        //$result = $this->pdo->query($query, PDO::FETCH_ASSOC);
        $row = $query->fetch();
        
        if ($row === false) {
            return false;
        }


        return $this->makeUserFromRow($row);
    }

    public function deleteByUsername($username)
    {
        $query = $this->db->prepare(self::DELETE_BY_NAME);

        return $query->execute(array($username));
    }

    public function setIsDoctorByUsername($username, $isDoctor)
    {
        $user = $this->findByUser($username);
        $user->setIsDoctor($isDoctor);
        if($this->saveExistingUser($user)){
            return 1;
        }else{
            return false;
        }
    }


    public function all()
    {
        $rows = $this->db->query(self::SELECT_ALL);
        
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

    public function saveNewUser(User $user)
    {

        $query = $this->db->prepare(self::INSERT_QUERY);

        return $query->execute(array(
            $user->getUsername(), $user->getHash(), $user->getEmail(), $user->getAge(), $user->getBio(), $user->isAdmin(), $user->isDoctor(), $user->getBanknumber(), $user->getFullname(), $user->getAddress(), $user->getPostcode()
        ));
    }

    public function saveExistingUser(User $user)
    {
        $query = $this->db->prepare(self::UPDATE_QUERY);

        return $query->execute(array(
            $user->getEmail(), $user->getAge(), $user->getBio(), $user->isAdmin(), $user->isDoctor(), $user->getBanknumber(), $user->getFullname(), $user->getAddress(), $user->getPostcode(), $user->getUserId()
        ));
    }
}
