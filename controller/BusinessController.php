<?php


require_once '../db/BusinessDAO.php';
require_once 'UserController.php';
require_once '../model/BusinessEmployeeHeader.php';

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
	
	 public function getDeskServiceById($id) {
       $businessDao = new BusinessDAO();
       $filter = array("ID" => $id, "BusinessID" => null,"Name"=>null, "SportelistID" => null, "isActive" => null);
       $deskServices = $businessDao->getDeskService($filter);
       if($deskServices == null || count($deskServices) == 0) {
           return null;
       }
       return $deskServices[0];
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
    public function getEmployeesByBusinessId($businessId) {
        if($businessId == null) {
            return array();
        }
        $businessDao = new BusinessDAO();
        $filter = array("BusinessID" => $businessId, "EmployeeID" => null, "Free"=>null, "IsActive"=>null, "NoManager"=>true);
        return $businessDao->getBusinessEmployeeHeader($filter);
    }

        public function getActiveDeskServiceByID($id)
        {
            $filter = array("ID" => $id, "BusinessID" => null, "Name" => null,"SportelistID" => null, "isActive" => 1);
            $businessDao = new BusinessDAO();
            $arr = $businessDao->getDeskService($filter);
            return $arr;
        }
    
        public function getDeskServicesByBusinessId($businessID)
        {
            if($businessID == null) {
                return array();
            }
            $filter = array("ID" => null, "BusinessID" => $businessID,"Name"=>null, "SportelistID" => null, "isActive" => null);
            $businessDao = new BusinessDAO();
            $deskServices = $businessDao->getDeskService($filter);
            return $deskServices;
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
        public function getDeskServiceCounter($deskServiceId) {
            $businessDao = new BusinessDAO();
            return $businessDao->getDeskServiceCounter($deskServiceId);
        }
        public function getDeskServiceBySportelistId($sportelistId) {
            $filter = array("ID" => null, "BusinessID" => null,"Name"=>null, "SportelistID" => $sportelistId, "isActive" => 0);
            $businessDao = new BusinessDAO();
            $arr = $businessDao->getDeskService($filter);
            $deskService = $arr[0];
            return $deskService;
        }
    
    
    
    public function saveBusiness(Business $business)
    {
        $businessDao = new BusinessDAO();
        return $businessDao->saveBusiness($business);
    }
    public function saveBusinessEmployeeHeader(BusinessEmployeeHeader $businessEmployeeHeader) {
        $businessDao = new BusinessDAO();
        $businessDao->saveBusinessEmployeeHeader($businessEmployeeHeader);
    }

    public function updateBusiness(Business $business)
    {
        $businessDao = new BusinessDAO();
        return $businessDao->updateBusiness($business, $business->getId());
    }
    public function deleteBusiness($businessId)
    {
        $businessDao = new BusinessDAO();
        $businessDao->deleteBusiness($businessId);
    }
    public function deleteBusinessEmployeeHeader($businessId, $employeeId) {
        $businessDao = new BusinessDAO();
        $businessDao->deleteBusinessEmployeeHeader($businessId, $employeeId);
    }


 

  

}
