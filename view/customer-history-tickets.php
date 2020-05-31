<?php
session_start();
if(isset($_SESSION["CustomerID"])) {
require_once '../controller/BusinessController.php';
require_once '../controller/TicketController.php';

$userID = $_SESSION['CustomerID'];
$ticketController = new TicketController();
$tickets=$ticketController->getTicketsByCustomerID($userID);
    require_once '../controller/ValidationController.php';
    $validationController = new ValidationController();
$businessController = new BusinessController();
$deskServices = null;
require_once '../controller/UserController.php';
$userController = new UserController();
$user = $userController->getUserByID($userID);
if(isset($_POST['cancelTicket'])){
    $ticketId=$_POST['cancelTicket'];
    $ticketController->cancelTicketCustomer($ticketId);
    $tickets=$ticketController->getInQueueTicketsByCustomerID($userID);
}

if (isset($_POST["saveProfile"])) {
    if ($_POST["name"] != "" && $_POST["name"] != null && strlen($_POST["name"]) != 0) {
        $user->setName($_POST["name"]);
    }
    if ($_POST["surname"] != "" && $_POST["surname"] != null && strlen($_POST["surname"]) != 0) {
        $user->setSurname($_POST["surname"]);
    }
    if ($_POST["username"] != "" && $_POST["username"] != null && strlen($_POST["username"]) != 0) {
        $user->setUsername($_POST["username"]);
    }
    if ($_POST["email"] != "" && $_POST["email"] != null && strlen($_POST["email"]) != 0) {
        $user->setEmail($_POST["email"]);
    }
    if ($_POST["phone"] != "" && $_POST["phone"] != null && strlen($_POST["phone"]) != 0) {
        $user->setPhone($_POST["phone"]);
    }
    if (isset($_POST["pass"]) && $_POST["pass"] != null && strlen($_POST["name"]) != 0) {
        $user->setPassword($_POST["pass"]);
        $validationController->checkForSignUp(array($user->getName(), $user->getSurname(), $user->getEmail(), $user->getPassword(), $user->getUsername(), $user->getPhone()));
        if ($validationController->getError() == 0)
            $userController->updateUser($user);
    } else {
        $validationController->checkForSignUp(array($user->getName(), $user->getSurname(), $user->getEmail(), $user->getPassword(), $user->getUsername(), $user->getPhone()));
        if ($validationController->getError() == 0)
            $userController->updateUserNoPass($user);
    }

    $user = $userController->getUserByID($userID);

}


?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="refresh"
          content="15; URL=customer-ticket.php?<?php echo "deskServiceID={$deskServiceID}&businessName={$businessName}" ?>">
    <title>Customer - History</title>
    <link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico" />

    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css"
          integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="css/iqueue_customer.css">

    <!-- Font Awesome JS -->
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js"
            integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ"
            crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js"
            integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY"
            crossorigin="anonymous"></script>


    <style type="text/css">

        #popup {
            width: 300px;;
            margin: 10px auto auto auto;
        }

        .border {
            display: inline-block;
            width: 300px;
            height: 163px;
            margin: 6px;
            border-radius: 5px;
        }

        #containerBox {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr;
            grid-template-rows: auto;
            justify-items: center;
            margin-top: 50px;
        }

        td {
            padding: 5px;
        }

        .people {
            animation: pulse 1s infinite;
            margin-top: 50px;
            animation-direction: alternate;
            -webkit-animation-name: pulse;
            animation-name: pulse;
        }

        @-webkit-keyframes pulse {
            0% {
                -webkit-transform: scale(1);
            }
            50% {
                -webkit-transform: scale(1.2);
            }
            100% {
                -webkit-transform: scale(1);
            }
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.2);
            }
            100% {
                transform: scale(1);
            }
        }

        @media only screen and (max-width: 600px) {
            .btn-outline-primary {
                width: 150px;
                height: 100px;
            }
        }
    </style>
</head>

<body>

<div class="wrapper">
    <!-- Sidebar  -->
    <nav id="sidebar">
        <div class="sidebar-header">
            <h3>iQueue</h3>
            <strong>BS</strong>
        </div>

        <ul class="list-unstyled components">
            <li class="active">
                <a href="customer-home.php">
                    <i class="fas fa-home"></i>
                    Home
                </a>
            </li>
            <li>
                <a href="customer-current-tickets.php">
                    <i class="fas fa-ticket-alt"></i>
                    Current Tickets
                </a>
                <a href="customer-history-tickets.php">
                    <i class="fas fa-history"></i>
                    History of Tickets
                </a>
            </li>
        </ul>

        <ul class="list-unstyled CTAs">
            <li>
                <a href="sign-out.php" class="signOut">Sign Out</a>
            </li>

        </ul>
    </nav>

    <!-- Page Content  -->
    <div id="content">


        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">

                <button type="button" id="sidebarCollapse" class="btn btn-info">
                    <i class="fas fa-align-left"></i>
                    <span>Menu</span>
                </button>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"
                        data-whatever="@mdo"><i class='fas fa-user-circle' style='font-size: 25px'
                                                value="<?php echo $_SESSION['CustomerID'] ?>"></i> Profile
                </button>
                <!--                <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse"-->
                <!--                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"-->
                <!--                        aria-expanded="false" aria-label="Toggle navigation">-->
                <!--                    <i class="fas fa-align-justify"></i>-->
                <!--                </button>-->

            </div>
        </nav>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <div class="d-flex align-items-stretch">
                <div class="page-holder w-100 d-flex flex-wrap">
                    <div class="container-fluid px-xl-5">
                        <section class="py-5">

                            <div class="row">
                                <div class="col-lg-12 mb-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="text-uppercase mb-0">My Tickets</h6>
                                        </div>
                                        <div class="card-body">
                                            <table class="table card-text">
                                                <thead>
                                                <tr>
                                                    <th>Business</th>
                                                    <th>Desk Service</th>
                                                    <th>Status</th>
                                                    <th>Ticket #</th>
                                                    <th>Reservation Date</th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                <?php
                                                foreach ($tickets as $ticket) {

                                                    echo "<tr";
                                                    if ($ticket->getStatus()->getId() == TicketStatus::$CANCELED) {
                                                        echo " style='color:red' ";
                                                    } else if ($ticket->getStatus()->getId() == TicketStatus::$COMPLETED) {
                                                        echo " style='color:green' ";
                                                    } else if ($ticket->getStatus()->getId() == TicketStatus::$IN_QUEUE) {
                                                        echo " style='color:blue' ";
                                                    } else if($ticket->getStatus()->getId() == TicketStatus::$CHECKED_IN) {
                                                        echo " style='color:orange' ";
                                                    }
                                                    echo ">
                                               
                                                 <td>" . $ticket->getBusiness()->getName() . "</td>
                                                 <td>" . $ticket->getDeskService()->getName() . "</td>
                                                 <td";

                                                        echo " style='min-width:140px;'>" . $ticket->getStatus()->getName();
                                                        if ($ticket->getStatus()->getId() == TicketStatus::$COMPLETED) {
                                                            echo " <i class=\"fas fa-check-circle\"></i>";
                                                        } else if ($ticket->getStatus()->getId() == TicketStatus::$IN_QUEUE) {
                                                            echo " <i class=\"fas fa-recycle\"></i>";
                                                        } else if ($ticket->getStatus()->getId() == TicketStatus::$CHECKED_IN) {
                                                            echo " <i class=\"fas fa-thumbs-up\"></i>";
                                                        } else if ($ticket->getStatus()->getId() == TicketStatus::$CANCELED) {
                                                            echo " <i class=\"fas fa-times\"></i>";
                                                        }
                                                        echo "</td>";
                                                 echo "<td>" . $ticket->getCount() . "</td>
                                                 <td>" . $ticket->getDate() . "</td>";

                                                }
                                                //                                                ?>
                                                </tbody>
                                            </table>

                                        </div>

                                    </div>

                                </div>

                            </div>
                        </section>
                    </div>
                </div>
            </div>
    </div>
    </form>
</div>


<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">My Profile</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"
                      enctype="multipart/form-data">
                    <div class="form-group">
                        <div class="container" style="width: 420px; margin: auto">
                            <div class="card" style="width:420px">
                                <img class="card-img-top" src="img/profile.png" alt="Card image"
                                     style="width:45%; margin: auto;">
                                <div class="card-body">
                                    <table>
                                        <tr>
                                            <td>Name</td>
                                            <td><input name="name" type="text" class="form-control"
                                                       value="<?php echo $user->getName(); ?>"></td>
                                        </tr>
                                        <tr>
                                            <td>Surname</td>
                                            <td><input name="surname" type="text" class="form-control"
                                                       value="<?php echo $user->getSurname(); ?>"></td>
                                        </tr>
                                        <tr>
                                            <td>Username</td>
                                            <td><input name="username" type="text" class="form-control"
                                                       value="<?php echo $user->getUsername(); ?>"></td>
                                        </tr>
                                        <tr>
                                            <td>Email</td>
                                            <td><input name="email" type="email" class="form-control"
                                                       id="exampleInputEmail1"
                                                       aria-describedby="emailHelp"
                                                       value="<?php echo $user->getEmail(); ?>"></td>
                                        </tr>
                                        <tr>
                                            <td>Phone</td>
                                            <td><input name="phone" type="text" class="form-control"
                                                       value="<?php echo $user->getPhone(); ?>"></td>
                                        </tr>
                                        <tr>
                                            <td>New Password</td>
                                            <td><input name="pass" type="password" class="form-control"
                                                       id="exampleInputPassword1"
                                                       placeholder="Password"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <br>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" name="saveProfile">Save</button>
                        </div>
                </form>
            </div>

        </div>
    </div>
</div>

</div>

<!-- jQuery CDN - Slim version (=without AJAX) -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
<!-- Popper.JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"
        integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ"
        crossorigin="anonymous"></script>
<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"
        integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm"
        crossorigin="anonymous"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $('#sidebarCollapse').on('click', function () {
            $('#sidebar').toggleClass('active');
        });
    });

</script>
</body>

</html>
<?php
}else{
    header("Location: index.php");
}

?>