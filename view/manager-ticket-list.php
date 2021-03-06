<?php
session_start();
if (isset($_SESSION["ManagerID"])) {
    require_once '../controller/TicketController.php';
    require_once '../controller/BusinessController.php';

    $userId = $_SESSION['ManagerID'];
    $ticketController = new TicketController();
    $businessController = new BusinessController();
    $businessID = $businessController->getBusinessIdByEmployeeId($userId);
    $tickets = $ticketController->getAllTicketsByBusinessID($businessID);
    $userController = new UserController();
    $user = $userController->getUserByID($userId);

    if (isset($_POST['back'])) {
        header("Location: manager-home.php");
    }
    ?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <title>iQueue Manager - Ticket</title>
        <link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico" />

        <!-- Bootstrap CSS CDN -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css"
              integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4"
              crossorigin="anonymous">
        <!-- Our Custom CSS -->
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/toggl-button.css">

        <!-- Font Awesome JS -->
        <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js"
                integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ"
                crossorigin="anonymous"></script>
        <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js"
                integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY"
                crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <!-- Latest compiled and minified CSS -->
        <!--    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">-->

        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

        <!-- Popper JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"></script>

        <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
        <!--    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>-->

        <style type="text/css">
            td {
                padding: 5px;
            }
        </style>
    </head>

    <body>

    <div class="wrapper">
        <!-- Sidebar  -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3>iQueue</h3>
                <strong>iQ</strong>
            </div>

            <ul class="list-unstyled components">

                <li>
                    <a href="manager-home.php">
                        <i class="fas fa-desktop"></i>
                        Desk Service
                    </a>

                </li>
                <li>
                    <a href="manager-employee-list.php">
                        <i class="fas fa-address-card"></i>
                        Employee
                    </a>

                </li>
                <li>
                    <a href="#">
                        <i class="fas fa-ticket-alt"></i>
                        Tickets
                    </a>

                </li>
<!--                <li>-->
<!--                    <a href="#">-->
<!--                        <i class="fas fa-chart-line"></i>-->
<!--                        Analytics-->
<!--                    </a>-->
<!--                </li>-->
                <!--<li style="margin-top: 10px">-->
                <!--<a href="#">-->
                <!--<i class="fas fa-user-circle"></i>-->
                <!--Profile-->
                <!--</a>-->
                <!--</li>-->
            </ul>

            <ul class="list-unstyled CTAs">
                <li>
                    <a href="sign-out.php" class="logout">Sign Out</a>
                </li>
                <!--<li>-->
                <!--<a href="https://bootstrapious.com/p/bootstrap-sidebar" class="article">Back to article</a>-->
                <!--</li>-->
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
                                                    value="<?php echo $_SESSION['ManagerID'] ?>"></i> Profile
                    </button>

                </div>
            </nav>
            <div class="d-flex align-items-stretch">
                <div class="page-holder w-100 d-flex flex-wrap">
                    <div class="container-fluid px-xl-5">
                        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
                            <button name="back" type="submit" class="btn btn-danger" id="registerBusiness">Go Back
                            </button>
                        </form>
                        <section class="py-5">

                            <div class="row">
                                <div class="col-lg-12 mb-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="text-uppercase mb-0">Tickets</h6>
                                        </div>
                                        <div class="card-body">
                                            <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
                                                <table class="table card-text">
                                                    <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Customer</th>
                                                        <th>Desk Service</th>
                                                        <th>Sportelist</th>
                                                        <th>Status</th>
                                                        <th>Count</th>
                                                        <th>Date</th>
                                                        <th>Notes</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>

                                                    <?php
                                                    foreach ($tickets as $ticket) {
                                                        $sportelist = $userController->getUserByID($ticket->getDeskService()->getSportelist());
                                                        echo "<tr";
                                                        if ($ticket->getStatus()->getId() == TicketStatus::$CANCELED) {
                                                            echo " style='color:red' ";
                                                        } else if ($ticket->getStatus()->getId() == TicketStatus::$COMPLETED) {
                                                            echo " style='color:green' ";
                                                        } else if ($ticket->getStatus()->getId() == TicketStatus::$IN_QUEUE) {
                                                            echo " style='color:blue' ";
                                                        } else if ($ticket->getStatus()->getId() == TicketStatus::$CHECKED_IN) {
                                                            echo " style='color:orange' ";
                                                        }
                                                        echo ">
                                                 <td>" . $ticket->getId() . "</td>
                                                 <td>" . $ticket->getCustomer()->getName() . " " . $ticket->getCustomer()->getSurname() . "</td>
                                                 <td>" . $ticket->getDeskService()->getName() . "</td>
                                                 <td>" . $sportelist->getName() . " " . $sportelist->getSurname() . "</td>
                                                 <td";

                                                        echo " style='min-width:140px;'>" . $ticket->getStatus()->getName();
                                                        if($ticket->getStatus()->getId() == TicketStatus::$COMPLETED) {
                                                            echo " <i class=\"fas fa-check-circle\"></i>";
                                                        } else if($ticket->getStatus()->getId() == TicketStatus::$IN_QUEUE) {
                                                            echo " <i class=\"fas fa-recycle\"></i>";
                                                        } else if($ticket->getStatus()->getId() == TicketStatus::$CHECKED_IN) {
                                                            echo " <i class=\"fas fa-thumbs-up\"></i>";
                                                        } else if($ticket->getStatus()->getId() == TicketStatus::$CANCELED) {
                                                            echo " <i class=\"fas fa-times\"></i>";
                                                        }
                                                         echo "</td>
                                                 <td>" . $ticket->getCount() . "</td>
                                                 <td>" . $ticket->getDate() . "</td>";


                                                        $checkout = $ticketController->getCheckOutByTicketId($ticket->getId());
                                                        if ($checkout != null)
                                                            echo "<td style='max-width: 300px'>{$checkout->getNote()}</td>";
                                                        else
                                                            echo "<td>No Notes</td>";


                                                        echo "</tr>";
                                                    }
                                                    ?>


                                                    </tbody>
                                                </table>
                                            </form>

                                        </div>

                                    </div>

                                </div>

                            </div>
                        </section>
                    </div>
                </div>
            </div>
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
}?>