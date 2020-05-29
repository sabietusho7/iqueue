<?php


require_once "../db/TicketDAO.php";
require_once "../model/Ticket.php";

class TicketController
{
    public function getTickets() {
        $filter = array("id"=>null,"CustomerID"=>null,"deskServiceId"=>null,"ticketStatusId"=>null, "inQueue"=>null,"BusinessID"=>null);
        $userDao = new TicketDAO();
        $user= $userDao->getTickets($filter);
        return $user;
    }
    public function getTicketsByCustomerID($id) {
        if($id == null) {
            return array();
        }
        $filter = array("id"=>null,"CustomerID"=>$id,"deskServiceId"=>null,"ticketStatusId"=>null, "inQueue"=>null,"BusinessID"=>null);
        $userDao = new TicketDAO();
        $user= $userDao->getTickets($filter);
        return $user;
    }
    public function getAllTicketsByBusinessID($businessID){
        $filter = array("id"=>null,"CustomerID"=>null,"deskServiceId"=>null,"ticketStatusId"=>null, "inQueue"=>null,"BusinessID"=>$businessID);
        $userDao = new TicketDAO();
        $ticket= $userDao->getTickets($filter);
        return $ticket;
    }
    public function getCheckOutByTicketId($ticketId) {
        $ticketDao = new TicketDAO();
        $checkInFilter = array("id"=>null,"ticketId"=>$ticketId);
        $checkIns = $ticketDao->getCheckIns($checkInFilter);
        if(count($checkIns)==0) {
            return null;
        }
        $checkIn = $checkIns[0];

        $checkOutFilter = array("id"=>null,"checkInId"=>$checkIn->getId());

        $checkOuts = $ticketDao->getCheckOuts($checkOutFilter);
        if(count($checkOuts)==0) {
            return null;
        }
        $checkOut = $checkOuts[0];
        return $checkOut;
    }
    public function getInQueueTicketsByCustomerID($id) {
        $filter = array("id"=>null,"CustomerID"=>$id,"deskServiceId"=>null,"ticketStatusId"=>null, "inQueue"=>true,"BusinessID"=>null);
        $userDao = new TicketDAO();
        $user= $userDao->getTickets($filter);
        return $user;
    }

    public function getTicketsByDeskServiceId($deskServiceId) {
        if($deskServiceId == null) {
            return array();
        }
        $filter = array("id"=>null,"CustomerID"=>null,"deskServiceId"=>$deskServiceId,"ticketStatusId"=>null, "inQueue"=>null,"BusinessID"=>null);
        $userDao = new TicketDAO();
        $user= $userDao->getTickets($filter);
        return $user;
    }

    public function getInQueueTicketsByDeskServiceId($deskServiceId) {
        if($deskServiceId == null) {
            return array();
        }
        $filter = array("id"=>null,"CustomerID"=>null,"deskServiceId"=>$deskServiceId,"ticketStatusId"=>null, "inQueue"=>true,"BusinessID"=>null);
        $userDao = new TicketDAO();
        $user= $userDao->getTickets($filter);
        return $user;
    }

    public function saveTicket(Ticket $ticket) {
        $ticketDao = new TicketDAO();
        $businessController = new BusinessController();

        $deskServiceId = $ticket->getDeskService();
        $count = $businessController->getDeskServiceCounter($deskServiceId);
        $ticket->setCount($count);
        $ticket->setStatus(TicketStatus::$IN_QUEUE);

        $ticket->setDate(date('Y-m-d H:i:s', time()));
        $ticketDao->saveTicket($ticket);
        $businessController->updateDeskServiceCounter($deskServiceId, $count + 1);
    }

    public function getQueueCounter($deskServiceID){
        $ticketDao = new TicketDAO();
        $arr= $ticketDao->getCountOFQueueByDeskID($deskServiceID);
        return $arr[0];
    }

    public function saveCheckIn(CheckIn $checkIn) {
        $ticketDao = new TicketDAO();

        $checkIn->setDate(date('Y-m-d H:i:s', time()));
        $ticketDao->saveCheckIn($checkIn);
        $ticketDao->checkInTicket($checkIn->getTicketId());

        $ticketDao->updateTicketStatus($checkIn->getTicketId(), TicketStatus::$CHECKED_IN);
    }

//    public function getCheckOutByTicketId($ticketId) {
//        $ticketDao = new TicketDAO();
//        $checkInFilter = array("id"=>null,"ticketId"=>$ticketId);
//        $checkIns = $ticketDao->getCheckIns($checkInFilter);
//        $checkIn = $checkIns[0];
//
//        $checkOutFilter = array("id"=>null,"checkInId"=>$checkIn->getId());
//
//        $checkOuts = $ticketDao->getCheckOuts($checkOutFilter);
//        $checkOut = $checkOuts[0];
//        return $checkOut;
//    }

    public function saveCheckOut(CheckOut $checkOut) {
        $ticketDao = new TicketDAO();
        $checkInFilter = array("id"=>null,"ticketId"=>$checkOut->getTicketId());
        $checkIns = $ticketDao->getCheckIns($checkInFilter);
        $checkIn = $checkIns[0];

        $checkOut->setCheckInId($checkIn->getId());
        $checkOut->setDate(date('Y-m-d H:i:s', time()));
        $ticketDao->saveCheckOut($checkOut);

        $ticketDao->updateTicketStatus($checkOut->getTicketId(), TicketStatus::$COMPLETED);
    }

    public function cancelTicketCustomer($ticketId) {
        $ticketDao = new TicketDAO();
        $ticketDao->updateTicketStatus($ticketId, TicketStatus::$CANCELED);
    }

    public function cancelTicketSportelist(CheckOut $checkOut) {
        $ticketDao = new TicketDAO();
        $checkInFilter = array("id"=>null,"ticketId"=>$checkOut->getTicketId());
        $checkIns = $ticketDao->getCheckIns($checkInFilter);
        $checkIn = $checkIns[0];

        $checkOut->setCheckInId($checkIn->getId());
        $checkOut->setDate(date('Y-m-d H:i:s', time()));
        $ticketDao->saveCheckOut($checkOut);

        $ticketDao->updateTicketStatus($checkOut->getTicketId(), TicketStatus::$CANCELED);
    }

    public function getTicketCompletionTime($ticketId) {
        $ticketDao = new TicketDAO();
        return $ticketDao->getTicketCompletionTime($ticketId);
    }
}