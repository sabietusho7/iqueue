<?php


require_once '../controller/BusinessController.php';
require_once '../controller/UserController.php';
require_once '../controller/ValidationController.php';
require_once '../model/Business.php';

session_start();
//$userId = $_SESSION['userId'];
$businessController = new BusinessController();
$userController = new UserController();

$businessValidationController = new ValidationController();
$userValidationController = new ValidationController();

$business = null;
if (isset($_POST['save'])) {
    $businessValidationController->checkForBusiness(array($_POST['phone']));
    $userValidationController->checkForUser(array(
            $_POST['managerUsername'], $_POST['managerName'], $_POST['managerSurname'], $_POST['managerEmail'], $_POST['managerPhone']));
    if ($businessValidationController->getError() == 0 && $userValidationController->getError() == 0) {
        $business = new Business(0);
        $business->setName($_POST['name']);
        $business->setAddress($_POST['address']);
        $business->setPhone($_POST['phone']);
        $business->setWebsiteUrl($_POST['website']);
        if (isset($_POST['active'])) {
            $business->setIsActive(1);
        } else {
            $business->setIsActive(0);
        }
        $businessId = $businessController->saveBusiness($business);

        $manager = new User(0);
        $manager->setUsername($_POST['managerUsername']);
        $manager->setPassword("iqueue123");
        $manager->setName($_POST['managerName']);
        $manager->setSurname($_POST['managerSurname']);
        $manager->setEmail($_POST['managerEmail']);
        $manager->setPhone($_POST['managerPhone']);
        $manager->setType(UserType::$BUSINESS_MANAGER);

        $managerId = $userController->saveUser($manager);

        $businessEmployeeHeader = new BusinessEmployeeHeader(0);
        $businessEmployeeHeader->setEmployeeId($managerId);
        $businessEmployeeHeader->setBusinessId($businessId);
        $businessController->saveBusinessEmployeeHeader($businessEmployeeHeader);

        if ($businessId > 0 && $managerId > 0) {
            header("Location: admin-home.php");
        }
    }
}

if (isset($_POST['update'])) {
    $businessValidationController->checkForBusiness(array($_POST['phone']));
    if ($businessValidationController->getError() == 0) {
        $business = new Business($_POST['id']);
        $business->setName($_POST['name']);
        $business->setAddress($_POST['address']);
        $business->setPhone($_POST['phone']);
        $business->setWebsiteUrl($_POST['website']);
        if (isset($_POST['active'])) {
            $business->setIsActive(1);
        } else {
            $business->setIsActive(0);
        }
        $success = $businessController->updateBusiness($business);
        if ($success) {
            header("location:admin-home.php");
        }
    }
}
if (isset($_GET['businessId'])) {
    $business = $businessController->getBusinessById($_GET['businessId']);
    if($business == null) {
        header("location:admin-home");
    }
}
if (isset($_POST['back'])) {
    header("location:admin-home.php");
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>iQueue Admin - Business</title>
    <link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico" />

    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css"
          integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
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
            font-size: 15px;
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
                <a href="admin-home.php">
                    <i class="fas fa-briefcase"></i>
                    Business
                </a>

            </li>
            <li>
                <a href="admin-customer-list.php">
                    <i class="fas fa-user-tie"></i>
                    Customer
                </a>

            </li>
            <li>
                <a href="admin-ticket-list.php">
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
            </div>
        </nav>

        <table>
            <tr>
                <td>
                    <?php
                    if ($business == null) {
                        echo " <div class=\"alert alert-info\" role=\"alert\" style=\"width:520px;margin-bottom:0px;\">
            <h6 style=\"color:red; margin-bottom:0px; width='400px'\">Default password for Business Manager will be: iqueue123</h6>
        </div>";
                    }
                    ?>
                </td>
                <td style="<?php if ($business == null) echo "padding-left:50px;" ?>">
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


        <form action="<?php echo htmlspecialchars($_SERVER["REQUEST_URI"]); ?>" method="post"
              enctype="multipart/form-data">
            <input name="id" style="display:none;" value=<?php echo "'";
            if (isset($_POST["id"]))
                echo $_POST["id"];
            else
                echo $business != null ? $business->getId() : 0;
            echo "'>"; ?>

            <div class="form-group">

            <label for="inputName">Name</label>
            <input name="name" type="text" class="form-control" id="inputName" value=
            <?php echo "'";
            if (isset($_POST["name"]))
                echo $_POST["name"];
            else echo $business != null ? $business->getName() : "";
            echo "'>"; ?>
            <?php
            if (!empty($businessValidationController->getNameError())) {
                echo '<p class="error">' . $businessValidationController->getNameError() . '</p>';
            }
            ?>
            </div>
            <div class="form-group">
                <label for="inputAddress">Address</label>
                <input name="address" type="text" class="form-control" id="inputAddress" value=
                <?php echo "'";
                if (isset($_POST["address"]))
                    echo $_POST["address"];
                else
                    echo $business != null ? $business->getAddress() : "";
                echo "'>"; ?>
                </div>
                <div class="form-group">
                    <label for="inputPhone">Phone Number</label>
                    <input name="phone" type="text" class="form-control" id="inputPhone" value=
                    <?php echo "'";
                    if (isset($_POST["phone"]))
                        echo $_POST["phone"];
                    else
                        echo $business != null ? $business->getPhone() : "";
                    echo "'>"; ?>
                    <?php
                    if (!empty($businessValidationController->getPhoneError())) {
                        echo '<p class="error">' . $businessValidationController->getPhoneError() . '</p>';
                    }
                    ?>
                    </div>
                    <div class="form-group">
                        <label for="inputAddress2">Website Address</label>
                        <input name="website" type="text" class="form-control" id="inputAddress2" value=
                        <?php echo "'";
                        if (isset($_POST["website"]))
                            echo $_POST["website"];
                        else
                            echo $business != null ? $business->getWebsiteUrl() : "";
                        echo "'>"; ?>
                        </div>


                        <?php
                        if ($business == null) {
                            echo "<div class=\"form-row\">
                            <div class=\"form-group col-md-6\">
                                <label for=\"inputEmail4\">Manager Username</label>
                                <input name='managerUsername' type=\"text\" class=\"form-control\" id=\"inputEmail4\" value='";
                            if (isset($_POST['managerUsername'])) echo $_POST['managerUsername'];
                            echo "'>";
                            if (!empty($userValidationController->getUsernameError())) {
                                echo '<p class="error">' . $userValidationController->getUsernameError() . '</p>';
                            }

                            echo "</div>
                            <div class=\"form-group col-md-6\">
                                <label for=\"inputEmail4\">Manager Name</label>
                                <input name='managerName' type=\"text\" class=\"form-control\" id=\"inputEmail4\" value='";
                            if (isset($_POST['managerName'])) echo $_POST['managerName'];
                            echo "'>";
                            if (!empty($userValidationController->getNameError())) {
                                echo '<p class="error">' . $userValidationController->getNameError() . '</p>';
                            }
                            echo "</div>
                            <div class=\"form-group col-md-6\">
                                <label for=\"inputPassword4\">Manager Surname</label>
                                <input name='managerSurname' type=\"text\" class=\"form-control\" id=\"inputPassword4\" value='";
                            if (isset($_POST['managerSurname'])) echo $_POST['managerSurname'];
                            echo "'>";
                            if (!empty($userValidationController->getSurnameError())) {
                                echo '<p class="error">' . $userValidationController->getSurnameError() . '</p>';
                            }
                            echo "</div>
                            <div class=\"form-group col-md-6\">
                                <label for=\"inputPassword4\">Manager Email</label>
                                <input name='managerEmail' type=\"text\" class=\"form-control\" id=\"inputPassword4\" value='";
                            if (isset($_POST['managerEmail'])) echo $_POST['managerEmail'];
                            echo "'>";
                            if (!empty($userValidationController->getEmailError())) {
                                echo '<p class="error">' . $userValidationController->getEmailError() . '</p>';
                            }
                            echo "</div> 
                            <div class=\"form-group col-md-6\">
                                <label for=\"inputPassword4\">Manager Phone</label>
                                <input name='managerPhone' type=\"text\" class=\"form-control\" id=\"inputPassword4\" value='";
                            if (isset($_POST['managerPhone'])) echo $_POST['managerPhone'];
                            echo "'>";
                            if (!empty($userValidationController->getPhoneError())) {
                                echo '<p class="error">' . $userValidationController->getPhoneError() . '</p>';
                            }
                            echo "</div>
                        </div>";
                        }
                        ?>


                        Turn <?php echo $business != null ? $business->getName() : "" ?> off/on
                        <br>
                        <!--                            <div class="form-group">-->
                        <!--                                <div class="form-check">-->
                        <label class='switch' style="margin-top: 5px;">
                            <input name='active' type='checkbox'
                                <?php echo $business != null ? (($business->getIsActive() == 1 ? 'checked>' : 'unchecked>')) : 'checked>'; ?>
                            >
                            <span class='slider round'></span>
                            <input id='hiddenIsActive' name='hiddenIsActive' type='hidden' value='No'>

                        </label>
                        <br>
                        <hr>
                        <!--                                </div>-->
                        <!--                            </div>-->


                        <button name="update" type="submit" class="btn btn-primary"
                                id="update" <?php if ($business == null) echo "style='display:none;'" ?> >Update Changes
                        </button>
                        <button name="save" type="submit" class="btn btn-primary"
                                id="update" <?php if ($business != null) echo "style='display:none;'" ?> >Save Changes
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
