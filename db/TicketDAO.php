<?php

require_once 'DBLayer.php';
require_once '../model/User.php';
require_once '../model/DeskService.php';
require_once '../model/TicketStatus.php';
require_once '../model/Business.php';
require_once '../model/CheckIn.php';
require_once '../model/CheckOut.php';
class TicketDAO extends DBLayer
{
    public function __construct()
    {
        parent::__construct();
    }

    // Start Ticket

    public function getTickets($filter) {
        $query = "SELECT ticket.*, " .
                 "       user.Name AS CustomerName, " .
                 "       user.Surname AS CustomerSurname, " .
                 "       user.Phone AS CustomerPhone, " .
                 "       deskservice.ID AS DeskServiceID, " .
                 "       deskservice.Name AS DeskServiceName, " .
                 "       deskservice.SportelistID AS DeskServiceSportelistID, " .
                 "       business.Name AS BusinessName, " .
                 "       ticketstatus.Name AS StatusName " .
                 "FROM ticket " .
                 "INNER JOIN user ON ticket.CustomerID = user.ID " .
                 "INNER JOIN ticketstatus ON ticket.StatusID = ticketstatus.ID " .
                 "INNER JOIN deskservice ON ticket.DeskServiceID = deskservice.ID " .
                 "INNER JOIN business ON deskservice.BusinessID = business.ID " .
                 "WHERE 1 = 1 ";

        if($filter['id'] != null) {
            $query .= " AND ticket.ID = " . $this->getRealEscapeString($filter['id']);
        }
        if($filter['CustomerID'] != null) {
            $query .= " AND ticket.CustomerID = " . $this->getRealEscapeString($filter['CustomerID']);
        }
        if($filter['deskServiceId'] != null) {
            $query .= " AND ticket.DeskServiceID = " . $this->getRealEscapeString($filter['deskServiceId']);
        }
        if($filter['ticketStatusId'] != null) {
            $query .= " AND ticket.StatusID = " . $this->getRealEscapeString($filter['ticketStatusId']);
        }
        if($filter['inQueue'] != null) {
            $query .= " AND (ticket.StatusID = " . TicketStatus::$IN_QUEUE
                    . " OR ticket.StatusID = " . TicketStatus::$CHECKED_IN . ") ";
        }
        if($filter["BusinessID"]!=null){
            $query.= "AND business.ID= {$filter["BusinessID"]} ";
        }

        $query .= " ORDER BY ticket.Date DESC ";

//        echo $query;

        $tickets = array();
        $result = $this->executeQuery($query);
        while ($row = mysqli_fetch_assoc($result)) {
            $ticket = new Ticket($row['ID']);

            $customer = new User($row['CustomerID']);
            $customer->setName($row['CustomerName']);
            $customer->setSurname($row['CustomerSurname']);
            $customer->setPhone($row['CustomerPhone']);
            $ticket->setCustomer($customer);

            $deskService = new DeskService($row['DeskServiceID']);
            $deskService->setName($row['DeskServiceName']);
            $deskService->setSportelist($row['DeskServiceSportelistID']);
            $ticket->setDeskService($deskService);

            $business = new Business(0);
            $business->setName($row['BusinessName']);
            $ticket->setBusiness($business);

            $ticketStatus = new TicketStatus($row['StatusID']);
            $ticketStatus->setName($row['StatusName']);
            $ticket->setStatus($ticketStatus);

            $ticket->setCount($row['Count']);
           // $ticket->setIsCheckedIn($row['IsCheckedIn']);
            $ticket->setDate($row['Date']);

            array_push($tickets, $ticket);
        }
        return $tickets;
    }
    
    public function saveTicket(Ticket $ticket) {
        $query = "INSERT INTO ticket(CustomerID, DeskServiceID, StatusID, Count, Date) " .
                 "VALUES ({$this->getRealEscapeString($ticket->getCustomer())} " .
                 "      , {$this->getRealEscapeString($ticket->getDeskService())} " .
                 "      , {$this->getRealEscapeString($ticket->getStatus())} " .
                 "      , {$this->getRealEscapeString($ticket->getCount())} " .
                 "      , '{$this->getRealEscapeString($ticket->getDate())}') ";

        $this->executeQuery($query);
    }

    public function updateTicket(Ticket $ticket) {
        $query = "UPDATE ticket " .
            "SET CustomerID = {$this->getRealEscapeString($ticket->getCustomer())} " .
            "  , DeskServiceID = {$this->getRealEscapeString($ticket->getDeskService())} " .
            "  , StatusID = {$this->getRealEscapeString($ticket->getStatus())} " .
            "  , Count = {$this->getRealEscapeString($ticket->getCount())} " .
            "  , Date = {$this->getRealEscapeString($ticket->getDate())} " .
            "WHERE ID = {$this->getRealEscapeString($ticket->getId())}";

        $this->executeQuery($query);
    }

    public function updateTicketStatus($ticketId, $statusId) {
        $query = "UPDATE ticket " .
                 "SET StatusID = {$this->getRealEscapeString($statusId)} " .
                 "WHERE ID = {$this->getRealEscapeString($ticketId)}";

        $this->executeQuery($query);
    }

    public function checkInTicket($ticketId) {
        $query = "UPDATE ticket " .
                 "SET IsCheckedIn = true " .
                 "WHERE ID = {$this->getRealEscapeString($ticketId)}";

        $this->executeQuery($query);
    }

    public function getTicketCompletionTime($ticketId) {
        $query = "SELECT TIMEDIFF(checkout.Date, checkin.Date) AS TimeDiff
                  FROM ticket, checkout, checkin 
                  WHERE ticket.ID = {$this->getRealEscapeString($ticketId)} AND checkout.CheckInID = checkin.ID AND checkin.TicketID = ticket.ID";
//        echo $query;
        $result = $this->executeQuery($query);
        $row = mysqli_fetch_assoc($result);
        return $row['TimeDiff'];
    }

    // End Ticket

    // Start Check in

    public function getCheckIns($filter)
    {
        $query = "SELECT * FROM checkin WHERE 1 = 1 ";
        if($filter['id'] != null) {
            $query .= " AND ID = " . $this->getRealEscapeString($filter['id']);
        }
        if($filter['ticketId'] != null) {
            $query .= " AND TicketID = " . $this->getRealEscapeString($filter['ticketId']);
        }

        $result = $this->executeQuery($query);
        $checkIns = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $checkIn = new CheckIn($row['ID']);
            $checkIn->setDeskServiceId($row['DeskServiceID']);
            $checkIn->setTicketId($row['TicketID']);

            array_push($checkIns, $checkIn);
        }
        return $checkIns;
    }

    public function saveCheckIn(CheckIn $checkIn) {
        $query = "INSERT INTO checkin(DeskServiceID, TicketID, Date) " .
                 "VALUES ({$this->getRealEscapeString($checkIn->getDeskServiceId())} " .
                 "      , {$this->getRealEscapeString($checkIn->getTicketId())} " .
                 "      , '{$this->getRealEscapeString($checkIn->getDate())}') ";
        $this->executeQuery($query);
    }

    // End Check in

    // Start Check out

    public function getCheckOuts($filter)
    {
        $query = "SELECT * FROM checkout WHERE 1 = 1 ";
        if($filter['id'] != null) {
            $query .= " AND ID = " . $this->getRealEscapeString($filter['id']);
        }
        if($filter['checkInId'] != null) {
            $query .= " AND checkInID = " . $this->getRealEscapeString($filter['checkInId']);
        }

        $result = $this->executeQuery($query);
        $checkOuts = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $checkOut = new CheckOut($row['ID']);
            $checkOut->setCheckInId($row['CheckInID']);
            $checkOut->setNote($row['Note']);

            array_push($checkOuts, $checkOut);
        }
        return $checkOuts;
    }

    public function saveCheckOut(CheckOut $checkOut) {
        $query = "INSERT INTO checkout(CheckInID, Note, Date) " .
                 "VALUES ({$this->getRealEscapeString($checkOut->getCheckInId())} " .
                 "      , '{$this->getRealEscapeString($checkOut->getNote())}' " .
                 "      , '{$this->getRealEscapeString($checkOut->getDate())}') ";
        $this->executeQuery($query);
    }

    public function getCountOFQueueByDeskID($deskServiceID){
        $ticketStatus= TicketStatus::$IN_QUEUE;
        $query="SELECT 
                COUNT(`ticket`.ID) as 'Count'
                FROM `ticket`
                INNER JOIN `deskservice` ON `deskservice`.`ID`= `ticket`.`DeskServiceID`
                INNER JOIN `ticketstatus` ON `ticketstatus`.ID= `ticket`.`StatusID`
                 WHERE `ticketstatus`.`ID` ={$this->getRealEscapeString($ticketStatus)}  AND `deskservice`.`ID`={$this->getRealEscapeString($deskServiceID)} ";
        $rows = array();
        $result = $this->executeQuery($query);
        while ($row = $result->fetch_assoc()) {
            array_push($rows, $row["Count"]);
        }
        return $rows;
    }

    // End Check out
}