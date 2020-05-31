<?php
session_start();
if(isset($_SESSION["ManagerID"])) {
    require_once '../controller/BusinessController.php';
    require_once '../controller/UserController.php';
    require_once '../model/Business.php';
    $userId = $_SESSION['ManagerID'];
    $businessController = new BusinessController();
    $businessId = $businessController->getBusinessIdByEmployeeId($userId);
    $employees = $businessController->getEmployeesByBusinessId($businessId);
    $userController = new UserController();
    $manager = $userController->getUserByID($userId);
    $employee = null;
    if (isset($_GET['modifyBusinessId'])) {
        $employee = $businessController->getBusinessById($_GET['modifyBusinessId']);
    }
    if (isset($_POST['modify'])) {
        $modify = $_POST['modify'];
        $businessId = $_POST['businessId'];
    }
    if (isset($_POST['registerEmployee'])) {
        header("location:manager-employee-info.php");
    }
    if (isset($_POST["saveProfile"])) {
        if ($_POST["name"] != "" && $_POST["name"] != null && strlen($_POST["name"]) != 0) {
            $manager->setName($_POST["name"]);
        }
        if ($_POST["surname"] != "" && $_POST["surname"] != null && strlen($_POST["surname"]) != 0) {
            $manager->setSurname($_POST["surname"]);
        }
        if ($_POST["username"] != "" && $_POST["username"] != null && strlen($_POST["username"]) != 0) {
            $manager->setUsername($_POST["username"]);
        }
        if ($_POST["email"] != "" && $_POST["email"] != null && strlen($_POST["email"]) != 0) {
            $manager->setEmail($_POST["email"]);
        }
        if ($_POST["phone"] != "" && $_POST["phone"] != null && strlen($_POST["phone"]) != 0) {
            $manager->setPhone($_POST["phone"]);
        }
        if ($_POST["pass"] != "" && $_POST["pass"] != null && strlen($_POST["pass"]) != 0) {
            $manager->setPassword($_POST["pass"]);
            $userController->updateUser($manager);
        } else {
            $userController->updateUserNoPass($manager);
        }

        $manager = $userController->getUserByID($userId);
        header("Location: manager-home.php");

    }

    ?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <title>iQueue Manager - Employee</title>
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
            td{
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
                    <a href="#">
                        <i class="fas fa-address-card"></i>
                        Employee
                    </a>

                </li>
                <li>
                    <a href="manager-ticket-list.php">
                        <i class="fas fa-ticket-alt"></i>
                        Tickets
                    </a>

                </li>

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
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"
                              enctype="multipart/form-data">
                            <button name="registerEmployee" type="submit" class="btn btn-primary" id="registerBusiness">
                                Register New Employee
                            </button>
                        </form>
                        <section class="py-5">

                            <div class="row">
                                <div class="col-lg-12 mb-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="text-uppercase mb-0">Businesses</h6>
                                        </div>
                                        <div class="card-body">
                                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
                                                  method="post" enctype="multipart/form-data">
                                                <table class="table card-text">
                                                    <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Username</th>
                                                        <th>Name</th>
                                                        <th>Surname</th>
                                                        <th>Email</th>
                                                        <th>Phone</th>
                                                        <th>Status</th>
                                                        <th>Modify</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>

                                                    <?php
                                                    foreach ($employees as $employee) {
                                                        echo "<tr>
                                                 <td>" . $employee->getFreeEmployee()->getId() . "</td>
                                                 <td>" . $employee->getFreeEmployee()->getUsername() . "</td>
                                                 <td>" . $employee->getFreeEmployee()->getName() . "</td>
                                                 <td>" . $employee->getFreeEmployee()->getSurname() . "</td>
                                                 <td>" . $employee->getFreeEmployee()->getEmail() . "</td>
                                                 <td>" . $employee->getFreeEmployee()->getPhone() . "</td>
                                                 <td style='padding-left:30px;'>";
                                                        if ($employee->getFreeEmployee()->getIsActive()) {
                                                            echo "<i class=\"fas fa-lock-open\"></i>";
                                                        } else {
                                                            echo "<i class=\"fas fa-lock\"></i>";
                                                        }
                                                        echo "</td>" .
//                                                 <td> <label class='switch'>
//                                                        <input name='isActive' type='checkbox' value='yes' ";
//                                                        echo ($business->getIsActive() == 1 ? 'checked>' : 'unchecked>');
//                                                echo "<span class='slider round'></span>
//                                                        <input id='hiddenIsActive' name='hiddenIsActive' type='hidden' value='No'>
//                                                    </label> </td>
                                                            " <td><a href='manager-employee-info.php?employeeId=" . $employee->getFreeEmployee()->getId() . "'><button type=\"button\" class=\"btn btn-primary\" data-toggle=\"modal\" data-target=\"#\" data-whatever=' " . $employee->getFreeEmployee()->getId() . "' ><i class='fas fa-edit' style='font-size: 20px'></i></button> </a> </td>";

                                                        echo "</tr>";
//                                                 <td><a href='editUser.php?id=" . $row['Id'] . "'>Edit</a></td>
//                                                 <td><button type='submit' name='delete' value='" . $row['Id'] . "'>Delete</button></td>
                                                    }
                                                    ?>
                                                    <!--                                            <tr>-->
                                                    <!--                                                <th scope="row">1</th>-->
                                                    <!--                                                <td>Mark</td>-->
                                                    <!--                                                <td>Otto</td>-->
                                                    <!--                                                <td>@mdo</td>-->
                                                    <!--                                                <td>-->
                                                    <!--                                                    <label class="switch">-->
                                                    <!--                                                        <input type="checkbox" checked>-->
                                                    <!--                                                        <span class="slider round"></span>-->
                                                    <!--                                                    </label>-->
                                                    <!--                                                </td>-->
                                                    <!--                                                <td>-->
                                                    <!--                                                    <i class='fas fa-edit' style='font-size: 30px'></i>-->
                                                    <!--                                                </td>-->
                                                    <!--                                            </tr>-->
                                                    <!--                                            <tr>-->
                                                    <!--                                                <th scope="row">2</th>-->
                                                    <!--                                                <td>Jacob</td>-->
                                                    <!--                                                <td>Thornton</td>-->
                                                    <!--                                                <td>@fat</td>-->
                                                    <!--                                                <td>-->
                                                    <!--                                                    <label class="switch">-->
                                                    <!--                                                        <input type="checkbox" checked>-->
                                                    <!--                                                        <span class="slider round"></span>-->
                                                    <!--                                                    </label>-->
                                                    <!--                                                </td>-->
                                                    <!--                                                <td>-->
                                                    <!--                                                    <i class="fas fa-edit" style="font-size: 30px"></i>-->
                                                    <!--                                                </td>-->
                                                    <!--                                            </tr>-->
                                                    <!--                                            <tr>-->
                                                    <!--                                                <th scope="row">3</th>-->
                                                    <!--                                                <td>Larry</td>-->
                                                    <!--                                                <td>the Bird</td>-->
                                                    <!--                                                <td>@twitter</td>-->
                                                    <!--                                                <td>-->
                                                    <!--                                                    <label class="switch">-->
                                                    <!--                                                        <input type="checkbox" checked>-->
                                                    <!--                                                        <span class="slider round"></span>-->
                                                    <!--                                                    </label>-->
                                                    <!--                                                </td>-->
                                                    <!--                                                <td>-->
                                                    <!--                                                    <i class="fas fa-edit" style="font-size: 30px"></i>-->
                                                    <!--                                                </td>-->
                                                    <!--                                            </tr>-->
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
                                                               value="<?php echo $manager->getName(); ?>"></td>
                                                </tr>
                                                <tr>
                                                    <td>Surname</td>
                                                    <td><input name="surname" type="text" class="form-control"
                                                               value="<?php echo $manager->getSurname(); ?>"></td>
                                                </tr>
                                                <tr>
                                                    <td>Username</td>
                                                    <td><input name="username" type="text" class="form-control"
                                                               value="<?php echo $manager->getUsername(); ?>"></td>
                                                </tr>
                                                <tr>
                                                    <td>Email</td>
                                                    <td><input name="email" type="email" class="form-control"
                                                               id="exampleInputEmail1"
                                                               aria-describedby="emailHelp"
                                                               value="<?php echo $manager->getEmail(); ?>"></td>
                                                </tr>
                                                <tr>
                                                    <td>Phone</td>
                                                    <td><input name="phone" type="text" class="form-control"
                                                               value="<?php echo $manager->getPhone(); ?>"></td>
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
}
else{
    header("Location: index.php");
}?>