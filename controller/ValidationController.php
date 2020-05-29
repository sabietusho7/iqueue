<?php

class ValidationController
{
    private $patternForName_Surname = "/^([A-Z])([a-z])+$/";
    private $patternForUsername = "/^[a-z\d\._-]+$/i";
    private $patternForEmail = "/^[a-z\d\._-]+@([a-z\d-]+\.)+[a-z]{2,6}$/i";
    private $patternForPassword = "/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{4,}$/";
    private $patternForPhone = "/^0\d{9}$/";
    private $patternForDeskService="/^([A-Z])[a-zA-Z ]+$/";
    private $nameError = "";
    private $surnameError = "";
    private $usernameError = "";
    private $passwordError = "";
    private $emailError = "";
    private $phoneError = "";
    private $etcError="";
    private $deskServiceError="";
    private $error = 0;

    /**
     * ValidationController constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return string
     */
    public function getNameError(): string
    {
        return $this->nameError;
    }

    /**
     * @return string
     */
    public function getSurnameError(): string
    {
        return $this->surnameError;
    }

    /**
     * @return string
     */
    public function getUsernameError(): string
    {
        return $this->usernameError;
    }

    /**
     * @return string
     */
    public function getPasswordError(): string
    {
        return $this->passwordError;
    }

    /**
     * @return string
     */
    public function getEmailError(): string
    {
        return $this->emailError;
    }

    /**
     * @return string
     */
    public function getPhoneError(): string
    {
        return $this->phoneError;
    }

    /**
     * @return int
     */
    public function getError(): int
    {
        return $this->error;
    }


    public function checkForSignUp($attributes)
    {
        $this->checkName($attributes[0]);
        $this->checkSurname($attributes[1]);
        $this->checkEmail($attributes[2]);
        $this->checkPassword($attributes[3]);
        $this->checkUsername($attributes[4]);
        $this->checkPhone($attributes[5]);

    }
  

    public function checkForUser($attributes) {
        $this->checkUsername($attributes[0]);
        $this->checkName($attributes[1]);
        $this->checkSurname($attributes[2]);
        $this->checkEmail($attributes[3]);
        $this->checkPhone($attributes[4]);
    }

    public function checkForBusiness($attributes)
    {

        $this->checkPhone($attributes[0]);
    }

    public function checkName($name)
    {
        if (!preg_match($this->patternForName_Surname, $name)) {
            $this->error++;
            $this->nameError = "Desk service must Contain only letters and spaces!";
        }
    }

    public function checkSurname($surname)
    {
        if (!preg_match($this->patternForName_Surname, $surname)) {
            $this->error++;
            $this->surnameError = "Only letters and the first letter must be an uppercase letter !";
        }
    }

    public function checkUsername($username)
    {
        if (!preg_match($this->patternForUsername, $username)) {
            $this->error++;
            $this->usernameError = "Username must  contain letters, numbers and the special characters . - _";
        }
    }

    public function checkPassword($pass)
    {
        if (!preg_match($this->patternForPassword, $pass)) {
            $this->error++;
            $this->passwordError = "Password must have at least 4 characters including a number !";
        }

    }

    public function checkEmail($email)
    {
        if (!preg_match($this->patternForEmail, $email)) {
            $this->error++;
            $this->emailError = "Email musts be in the formal format style: example1@something.com !";
        }
    }

    public function checkPhone($phone)
    {
        if (!preg_match($this->patternForPhone, $phone)) {
            $this->error++;
            $this->phoneError = "Must be an Albanian phone number : 06XXXXXXXX !";
        }
    }
   

}
