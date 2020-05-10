<?php


require_once 'DBLayer.php';
require_once '../model/User.php';
require_once '../model/UserType.php';

class UserDAO extends DBLayer
{
    public function __construct()
    {
        parent::__construct();
    }

    // Start User

    public function getUsers($filter)
    {
        $query = "SELECT user.*, " .
                 "       usertype.Name AS TypeName " .
                 "FROM user " .
                 "INNER JOIN usertype ON user.TypeID = usertype.ID " .
                 "WHERE 1 = 1 ";

        if ($filter['id'] != null) {
            $query .=" AND user.ID = " . $this->getRealEscapeString($filter['id']);
        }
        if ($filter['username'] != null) {
            $query .=" AND user.Username = '{$this->getRealEscapeString($filter['username'])}'";
        }
        if ($filter['password'] != null) {
            $query .=" AND user.Password = '{$this->getRealEscapeString($filter['password'])}'";
        }
        if ($filter['nameSurname'] != null) {
            $query .=" AND (user.Name LIKE '%" . $this->getRealEscapeString($filter['nameSurname']) . "%' OR user.Surname LIKE '%" . $this->getRealEscapeString($filter['nameSurname']) . "%' ";
        }
        if ($filter['phone'] != null) {
            $query .=" AND user.Phone LIKE '%" . $this->getRealEscapeString($filter['phone']) . ")%' ";
        }
        if ($filter['typeId'] != null) {
            $query .=" AND user.TypeID = " . $this->getRealEscapeString($filter['typeId']);
        }


        $result = $this->executeQuery($query);
        $users = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $user = new User($row['ID']);
            $user->setUsername($row['Username']);
            $user->setPassword($row['Password']);
            $user->setName($row['Name']);
            $user->setSurname($row['Surname']);
            $user->setEmail($row['Email']);
            $user->setPhone($row['Phone']);

            $userType = new UserType($row['TypeID']);
            $userType->setName($row['TypeName']);
            $user->setType($userType);

            $user->setIsActive($row['IsActive']);

            array_push($users, $user);
        }
        return $users;
    }

    public function saveUser(User $user)
    {
        $query = "INSERT INTO user(Username, Password, Name, Surname, Email, Phone, TypeID) " .
            "VALUES ('{$this->getRealEscapeString($user->getUsername())}' " .
            "      , '".md5($this->getRealEscapeString($user->getPassword()))."' " .
            "      , '{$this->getRealEscapeString($user->getName())}' " .
            "      , '{$this->getRealEscapeString($user->getSurname())}' " .
            "      , '{$this->getRealEscapeString($user->getEmail())}' " .
            "      , '{$this->getRealEscapeString($user->getPhone())}' " .
            "      , {$this->getRealEscapeString($user->getType())})";
        $this->executeQuery($query);
        return $this->getGeneratedId();
    }

    public function saveUserWithActive(User $user)
    {
        $query = "INSERT INTO user(Username, Password, Name, Surname, Email, Phone, TypeID, IsActive) " .
            "VALUES ('{$this->getRealEscapeString($user->getUsername())}' " .
            "      , '".md5($this->getRealEscapeString($user->getPassword()))."' " .
            "      , '{$this->getRealEscapeString($user->getName())}' " .
            "      , '{$this->getRealEscapeString($user->getSurname())}' " .
            "      , '{$this->getRealEscapeString($user->getEmail())}' " .
            "      , '{$this->getRealEscapeString($user->getPhone())}' " .
            "      , {$this->getRealEscapeString($user->getType())} " .
            "      , {$this->getRealEscapeString($user->getisActive())})";
        $this->executeQuery($query);
        return $this->getGeneratedId();
    }


    // Start UserType

    public function getUserTypes()
    {
        $query = "SELECT * FROM usertype";

        $result = $this->executeQuery($query);
        $userTypes = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $userType = new UserType($row['ID']);
            $userType->setName($row['Name']);

            array_push($userTypes, $userType);
        }
        return $userTypes;
    }
    public function updateUser(User $user)
    {
        $query = "UPDATE `user` SET 
             `Username` = '{$this->getRealEscapeString($user->getUsername())}',
             `Password` = '".md5($this->getRealEscapeString($user->getPassword()))."',
             `Name` = '{$this->getRealEscapeString($user->getName())}', 
             `Surname` = '{$this->getRealEscapeString($user->getSurname())}', 
             `Email` = '{$this->getRealEscapeString($user->getEmail())}',
             `Phone` = '{$this->getRealEscapeString($user->getPhone())}',
             `IsActive` = '{$this->getRealEscapeString($user->getisActive())}'
              WHERE `ID` = {$this->getRealEscapeString($user->getId())}";


        $this->executeQuery($query);
    }

    // End UserType



    // End Rates
}
