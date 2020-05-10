<?php


require_once '../db/BusinessDAO.php';
require_once 'UserController.php';

class BusinessController
{

    /**
     * BusinessController constructor.
     */
    public function __construct()
    {
    }

    public function getActiveBusinessById($id)
    {
        $filter = array("ID" => $id, "Name" => null, "isActive" => 1);
        $businessDao = new BusinessDAO();
        $arr = $businessDao->getBusinesses($filter);
        $business = $arr[0];
        return $business;
    }

    public function getBusinessById($id)
    {
        $filter = array("ID" => $id, "Name" => null, "isActive" => null);
        $businessDao = new BusinessDAO();
        $arr = $businessDao->getBusinesses($filter);
        $business = $arr[0];
        return $business;
    }

    public function getActiveBusinessByName($name)
    {
        $filter = array("ID" => null, "Name" => $name, "isActive" => 1);
        $businessDao = new BusinessDAO();
        $arr = $businessDao->getBusinesses($filter);
        $business = $arr[0];
        return $business;
    }

    public function getActiveBusinesses()
    {
        $filter = array("ID" => null, "Name" => null, "isActive" => 1);
        $businessDao = new BusinessDAO();
        $arr = $businessDao->getBusinesses($filter);
        return $arr;
    }

    public function getBusinesses()
    {
        $filter = array("ID" => null, "Name" => null, "isActive" => null);
        $businessDao = new BusinessDAO();
        $arr = $businessDao->getBusinesses($filter);
        return $arr;
    }
    
    public function getBusinessIdByEmployeeId($employeeId) {
        $businessDao = new BusinessDAO();
        $filter = array("BusinessID" => null, "EmployeeID" => $employeeId, "Free" => null, "IsActive" => 1, "NoManager" => null);
         $businessEmployeeHeaders = $businessDao->getBusinessEmployeeHeader($filter);
         $businessEmployeeHeader = $businessEmployeeHeaders[0];
         return $businessEmployeeHeader->getBusinessId();
    }

    public function getActiveDeskServiceByBusinessId($id)
    {
        if($id == null) {
            return array();
        }
        $filter = array("ID" => null, "BusinessID" => $id, "Name" => null,"SportelistID" => null, "isActive" => 1);
        $businessDao = new BusinessDAO();
        return $businessDao->getDeskService($filter);

    }
    
 

  

}