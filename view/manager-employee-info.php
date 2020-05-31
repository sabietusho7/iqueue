<?php
session_start();
if (isset($_SESSION["ManagerID"])) {
    require_once '../controller/BusinessController.php';
    require_once '../controller/UserController.php';
    require_once '../controller/ValidationController.php';
    require_once '../model/Business.php';

    $userId = $_SESSION['ManagerID'];
    $userController = new UserController();
    $manager = $userController->getUserByID($userId);
    $businessController = new BusinessController();
    $userController = new UserController();
    $employee = null;

    $userValidationController = new ValidationController();

    if (isset($_POST['save'])) {
        $userValidationController->checkForUser(array($_POST['username'], $_POST['name'], $_POST['surname'], $_POST['email'], $_POST['phone']));
        if ($userValidationController->getError() == 0) {
            $employee = new User(0);
            $employee->setUsername($_POST['username']);
            $employee->setName($_POST['name']);
            $employee->setSurname($_POST['surname']);
            $employee->setEmail($_POST['email']);
            $employee->setPhone($_POST['phone']);
            $employee->setType(UserType::$SPORTELIST);
            if (isset($_POST['active'])) {
                $employee->setIsActive(1);
            } else {
                $employee->setIsActive(0);
            }
            $employee->setPassword("iqueue123");
            $employeeId = $userController->saveUserWithActive($employee);
            $businessId = $businessController->getBusinessIdByEmployeeId($userId);

            $businessEmployeeHeader = new BusinessEmployeeHeader(0);
            $businessEmployeeHeader->setEmployeeId($employeeId);
            $businessEmployeeHeader->setBusinessId($businessId);
            $businessController->saveBusinessEmployeeHeader($businessEmployeeHeader);

            if ($employeeId > 0) {
                header("location:manager-employee-list.php");
            }
        }

    }
    if (isset($_POST['update'])) {
        $userValidationController->checkForUser(array($_POST['username'], $_POST['name'], $_POST['surname'], $_POST['email'], $_POST['phone']));
        if ($userValidationController->getError() == 0) {
            $employee = new User($_POST['id']);
            $employee->setUsername($_POST['username']);
            $employee->setName($_POST['name']);
            $employee->setSurname($_POST['surname']);
            $employee->setEmail($_POST['email']);
            $employee->setPhone($_POST['phone']);
            if (isset($_POST['active'])) {
                $employee->setIsActive(1);
            } else {
                $employee->setIsActive(0);
            }
            $success = $userController->updateUserNoPass($employee);
            if ($success) {
                header("location:manager-employee-list.php");
            }
        }
    }
    if (isset($_GET['employeeId'])) {
        $employee = $userController->getUserByID($_GET['employeeId']);
        if($employee == null) {
            header("location: manager-employee-list.php");
        }
        $businessId = $businessController->getBusinessIdByEmployeeId($manager->getId());
        $employees = $businessController->getEmployeesByBusinessId($businessId);
        $flag = true;
        foreach($employees as $emp) {
            if($emp->getId() == $employee->getId()) {
                $flag = false;
            }
        }
        if($flag) {
            header("location: manager-employee-list.php");
        }
    }
    if (isset($_POST['back'])) {
        header("location:manager-employee-list.php");
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
            .error {
                color: #bf1800;
                margin-top: 10px;
                text-align: left;
                margin-bottom: 0px;
                font-size:15px;
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
                    <a href="manager-employee-list.php ">
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
                    <button class="btn btn-dark d-inline-block d-lg-none ml-autos" type="button">
                        <i class="fas fa-user-circle" style="font-size: 1.8em;"></i>
                        <span style="font-size: 20px">Profile</span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="nav navbar-nav ml-auto">

                            <li class="nav-item" style="text-align: right;">
                                <i class="fas fa-user-circle" style="font-size: 1.8em;"></i>
                                <span style="font-size: 20px">Profile</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <table>
                <tr>
                    <td>
                        <?php
                        if ($employee == null) {
                            echo " <div class=\"alert alert-info\" role=\"alert\" style=\"width:520px;margin-bottom:0px;\">
            <h6 style=\"color:red; margin-bottom:0px; width='400px'\">Default password for Business Employee will be: iqueue123</h6>
        </div>";
                        }
                        ?>
                    </td>
                    <td style="<?php if ($employee == null) echo "padding-left:50px;" ?>">
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"
                              enctype="multipart/form-data">
                            <button style="height:45px" name="back" type="submit" class="btn btn-danger"
                                    id="registerBusiness">Go Back
                            </button>
                        </form>
                    </td>
                </tr>
            </table>
            <br>


            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"
                  enctype="multipart/form-data">
                <input name="id" style="display:none;" value=<?php echo "'";
                if (isset($_POST["id"]))
                    echo $_POST["id"];
                else
                echo $employee != null ? $employee->getId() : 0;
                echo "'>"; ?>
                <div class="form-group">
                <label for="inputName">Username</label>
                <input name="username" type="text" class="form-control" id="inputName" value=
                <?php echo "'";
                if (isset($_POST["username"]))
                    echo $_POST["username"];
                else
                echo $employee != null ? $employee->getUsername() : "";
                echo "'>"; ?>
                <?php if (!empty($userValidationController->getUsernameError())) {
                echo '<p class="error">' . $userValidationController->getUsernameError() . '</p>';
                } ?>
                </div>
                <div class="form-group">

                    <label for="inputName">Name</label>
                    <input name="name" type="text" class="form-control" id="inputName" value=
                    <?php echo "'";
                    if (isset($_POST["name"]))
                        echo $_POST["name"];
                    else
                    echo $employee != null ? $employee->getName() : "";
                    echo "'>"; ?>
                    <?php if (!empty($userValidationController->getNameError())) {
                        echo '<p class="error">' . $userValidationController->getNameError() . '</p>';
                    } ?>
                    </div>
                    <div class="form-group">
                        <label for="inputAddress">Surname</label>
                        <input name="surname" type="text" class="form-control" id="inputAddress" value=
                        <?php echo "'";
                        if (isset($_POST["surname"]))
                            echo $_POST["surname"];
                        else
                        echo $employee != null ? $employee->getSurname() : "";
                        echo "'>"; ?>
                        <?php if (!empty($userValidationController->getSurnameError())) {
                            echo '<p class="error">' . $userValidationController->getSurnameError() . '</p>';
                        } ?>
                        </div>
                        <div class="form-group">
                            <label for="inputPhone">Phone Number</label>
                            <input name="phone" type="text" class="form-control" id="inputPhone" value=
                            <?php echo "'";
                            if (isset($_POST["phone"]))
                                echo $_POST["phone"];
                            else
                            echo $employee != null ? $employee->getPhone() : "";
                            echo "'>"; ?>
                            <?php if (!empty($userValidationController->getPhoneError())) {
                                echo '<p class="error">' . $userValidationController->getPhoneError() . '</p>';
                            } ?>
                            </div>
                            <div class="form-group">
                                <label for="inputAddress2">Email</label>
                                <input name="email" type="text" class="form-control" id="inputAddress2" value=
                                <?php echo "'";
                                if (isset($_POST["email"]))
                                    echo $_POST["email"];
                                else
                                echo $employee != null ? $employee->getEmail() : "";
                                echo "'>"; ?>
                                <?php if (!empty($userValidationController->getEmailError())) {
                                    echo '<p class="error">' . $userValidationController->getEmailError() . '</p>';
                                } ?>
                                </div>

                                Turn <?php echo $employee != null ? $employee->getName() : "" ?> off/on
                                <br>
                                <!--                            <div class="form-group">-->
                                <!--                                <div class="form-check">-->
                                <label class='switch' style="margin-top: 5px;">
                                    <input name='active' type='checkbox'
                                        <?php echo $employee != null ? (($employee->getIsActive() == 1 ? 'checked>' : 'unchecked>')) : 'checked>'; ?>
                                    >
                                    <span class='slider round'></span>
                                    <input id='hiddenIsActive' name='hiddenIsActive' type='hidden' value='No'>

                                </label>
                                <br>
                                <hr>
                                <!--                                </div>-->
                                <!--                            </div>-->


                                <button name="update" type="submit" class="btn btn-primary"
                                        id="update" <?php if ($employee == null) echo "style='display:none;'" ?> >Save
                                    Changes
                                </button>
                                <button name="save" type="submit" class="btn btn-primary"
                                        id="update" <?php if ($employee != null) echo "style='display:none;'" ?> >Save
                                    Changes
                                </button>

            </form>
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
} else {
    header("Location: index.php");
}
?>