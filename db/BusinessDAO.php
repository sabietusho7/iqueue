<?php



require_once 'DBLayer.php';
require_once '../model/Business.php';



class BusinessDAO extends DBLayer
{

    /**
     * BusinessDAO constructor.
     */
    public function __construct()
    {
        parent::__construct();

    }

    public function getBusinesses($filter)
    {
        $query = "SELECT business.*
                         , user.ID AS ManagerID
                         , user.Name AS ManagerName
                         , user.Surname AS ManagerSurname
                         , user.Email AS ManagerEmail
                         , user.Phone AS ManagerPhone
                         , user.IsActive AS ManagerIsActive
                  FROM `business`, user, businessemployeeheader
                  WHERE businessemployeeheader.BusinessID = business.ID
                  AND businessemployeeheader.EmployeeID = user.ID
                  AND user.TypeID = " . UserType::$BUSINESS_MANAGER . "
                   ";
        $rows = array();
        if ($filter["ID"] != null) {
            $id = $this->getRealEscapeString($filter["ID"]);
            $query .= " AND business.`ID`= $id";
        }
        if ($filter["Name"] != null) {
            $name = $this->getRealEscapeString($filter["Name"]);
            $query .= " AND business.`Name` like '%$name%' ";
        }
        if ($filter["isActive"] != null) {
            $isActive = $this->getRealEscapeString($filter["isActive"]);
            $query .= " AND business.`IsActive`={$isActive} ";
        }

        $result = $this->executeQuery($query);
        while ($row = $result->fetch_assoc()) {
            $business = new Business($row["ID"]);
            $business->setName($row["Name"]);
            $business->setAddress($row["Address"]);
            $business->setPhone($row["Phone"]);

            $manager = new User($row["ManagerID"]);
            $manager->setName($row["ManagerName"]);
            $manager->setSurname($row["ManagerSurname"]);
            $manager->setEmail($row["ManagerEmail"]);
            $manager->setPhone($row["ManagerPhone"]);
            $manager->setIsActive($row["ManagerIsActive"]);
            $business->setManager($manager);

            $business->setImgUrl($row["ImgURL"]);
            $business->setWebsiteUrl($row["WebsiteURL"]);
            $business->setIsActive($row["IsActive"]);
            array_push($rows, $business);
        }
        return $rows;
    }

   public function getBusinessEmployeeHeader($filter)
    {
        $query = "SELECT `businessemployeeheader`.*
                          , user.Username AS UserUsername
                          , user.Name AS UserName
                          , user.Surname AS UserSurname
                          , user.Email AS UserEmail
                          , user.Phone AS UserPhone
                          , user.TypeID AS UserTypeID
                          , user.IsActive AS UserIsActive
                  FROM `businessemployeeheader`
                  INNER JOIN user ON businessemployeeheader.EmployeeID = user.ID
                  INNER JOIN business on businessemployeeheader.BusinessID = business.ID
                  WHERE 1=1 ";
        if ($filter["BusinessID"] != null) {
            $query .= " AND `BusinessID`={$this->getRealEscapeString($filter["BusinessID"])}";
        }
        if ($filter["EmployeeID"] != null) {
            $query .= " AND `EmployeeID`={$this->getRealEscapeString($filter["EmployeeID"])}";
        }
        if ($filter["Free"] != null) {
            $query .= " AND user.TypeID = " . UserType::$SPORTELIST .
                " AND NOT EXISTS( SELECT * FROM deskservice
                                    WHERE deskservice.SportelistID = user.ID) ";
        }
        if ($filter["IsActive"] != null) {
            $query .= " AND user.IsActive ={$this->getRealEscapeString($filter["IsActive"])}";
        }
        if ($filter["NoManager"] != null) {
            $query .= " AND user.TypeID <> " . UserType::$BUSINESS_MANAGER;
        }

        $rows = array();
        $result = $this->executeQuery($query);
        while ($row = mysqli_fetch_assoc($result)) {
            $businessEmployeeHeader = new BusinessEmployeeHeader($row["ID"]);
            $businessEmployeeHeader->setBusinessId($row["BusinessID"]);
            $businessEmployeeHeader->setEmployeeId($row["EmployeeID"]);

            $freeEmployee = new User($row["EmployeeID"]);
            $freeEmployee->setUsername($row["UserUsername"]);
            $freeEmployee->setName($row["UserName"]);
            $freeEmployee->setSurname($row["UserSurname"]);
            $freeEmployee->setEmail($row["UserEmail"]);
            $freeEmployee->setPhone($row["UserPhone"]);
            $freeEmployee->setType($row["UserTypeID"]);
            $freeEmployee->setIsActive($row["UserIsActive"]);
            $businessEmployeeHeader->setFreeEmployee($freeEmployee);

            array_push($rows, $businessEmployeeHeader);
        }
        return $rows;
    }

}