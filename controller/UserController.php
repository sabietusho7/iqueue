<?php

require_once '../db/UserDAO.php';
require_once '../model/User.php';


class UserController
{
    public function __construct()
    {
        //default constructor
    }

    // Start User
    public function getUserByUsernamePassword($username, $password) {
        $filter = array("id"=>null,"username"=>$username,"password"=>md5($password),"nameSurname"=>null,"phone"=>null,"typeId"=>null);
        $userDao = new UserDAO();
        $user= $userDao->getUsers($filter);
        return $user;
    }

    public function getUserByID($id) {
        $filter = array("id"=>$id,"username"=>null,"password"=>null,"nameSurname"=>null,"phone"=>null,"typeId"=>null);
        $userDao = new UserDAO();
        $user = $userDao->getUsers($filter)[0];
        return $user;
    }

    public function getUsers() {
        $filter = array("id"=>null,"username"=>null,"password"=>null,"nameSurname"=>null,"phone"=>null,"typeId"=>null);
        $userDao = new UserDAO();
        $user= $userDao->getUsers($filter);
        return $user;
    }

    public function getCustomers() {
        $filter = array("id"=>null,"username"=>null,"password"=>null,"nameSurname"=>null,"phone"=>null,"typeId"=>UserType::$CUSTOMER);
        $userDao = new UserDAO();
        $user= $userDao->getUsers($filter);
        return $user;
    }

    public function getUserTypes() {
        $userDao = new UserDAO();
        return $userDao->getUserTypes();
    }
    public function updateUser(User $user){
        $userDao = new UserDAO();
        return $userDao->updateUser($user);
    }
    public function updateUserNoPass(User $user){
        $userDao = new UserDAO();
        return $userDao->updateUserNoPassword($user);
    }

    public function saveUser(User $user) {

        $userDao = new UserDAO();
        return $userDao->saveUser($user);
    }

    public function saveUserWithActive(User $user) {
        $userDao = new UserDAO();
        return $userDao->saveUserWithActive($user);
    }
  
    public function deleteUser($userId) {
        $userDao = new UserDAO();
        $userDao->deleteUser($userId);
    }
}
