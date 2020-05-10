<?php
/**
 * Created by PhpStorm.
 * User: grent
 * Date: 1/21/2019
 * Time: 8:14 PM
 */

class BusinessEmployeeHeader
{
    private $id;
    private $businessId;
    private $employeeId;
    private $freeEmployee;



    /**
     * businessHeader constructor.
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
    public function getBusinessId()
    {
        return $this->businessId;
    }

    /**
     * @param mixed $businessId
     */
    public function setBusinessId($businessId)
    {
        $this->businessId = $businessId;
    }

    /**
     * @return mixed
     */
    public function getEmployeeId()
    {
        return $this->employeeId;
    }

    /**
     * @param mixed $employeeId
     */
    public function setEmployeeId($employeeId)
    {
        $this->employeeId = $employeeId;
    }

    /**
     * @return mixed
     */
    public function getFreeEmployee()
    {
        return $this->freeEmployee;
    }

    /**
     * @param mixed $freeEmployee
     */
    public function setFreeEmployee($freeEmployee): void
    {
        $this->freeEmployee = $freeEmployee;
    }


}