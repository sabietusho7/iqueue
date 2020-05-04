<?php

class UserType
{
    public static $IQUEUE_ADMIN  = 1;
    public static $BUSINESS_MANAGER = 2;
    public static $SPORTELIST = 3;
    public static $CUSTOMER= 4;

    private $id;
    private $name;

    /**
     * userType constructor.
     * @param $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }


}