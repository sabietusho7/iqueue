<?php
session_start();
if (isset($_SESSION["ManagerID"])) {
    require_once '../controller/BusinessController.php';
    require_once '../controller/UserController.php';
    require_once '../model/Business.php';
    $userId = $_SESSION['ManagerID'];
    $businessController = new BusinessController();
    $userController = new UserController();
    $manager = $userController->getUserByID($userId);
    $businessId = $businessController->getBusinessIdByEmployeeId($manager->getID());
    $deskService = null;

    if (isset($_GET['deskServiceId'])) {
        $deskService = $businessController->getDeskServiceById($_GET['deskServiceId']);
        if($deskService == null) {
            header("location: manager-home.php");
        }
        if($businessId != $deskService->getBusinessId()) {
            header("location:manager-home.php");
        }
    }
    if (isset($_POST['save'])) {
        $deskService = new DeskService(0);
        $deskService->setName($_POST['name']);

        $deskService->setBusinessId($businessId);
        $deskService->setEtc($_POST['etc']);
        $deskService->setSportelist($_POST['sportelists']);
        if (isset($_POST['active'])) {
            $deskService->setIsActive(1);
        } else {
            $deskService->setIsActive(0);
        }
        $businessController->saveDeskService($deskService);
        header("location:manager-home.php");
    }
    if (isset($_POST['update'])) {
        $deskService = new DeskService($_POST['id']);
        $deskService->setName($_POST['name']);
        $deskService->setBusinessId($businessId);
        $deskService->setEtc($_POST['etc']);
        $deskService->setSportelist($_POST['sportelists']);
        if (isset($_POST['active'])) {
            $deskService->setIsActive(1);
        } else {
            $deskService->setIsActive(0);
        }
        $businessController->updateDeskService($deskService);
        header("location:manager-home.php");
    }
    if (isset($_POST['back'])) {
        header("location:manager-home.php");
    }
    $sportelists = $businessController->getFreeEmployeesByBusinessId($businessId);

    ?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">


        <title>iQueue Manager - Desk Service</title>
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
            </nav>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"
                  enctype="multipart/form-data">
                <button name="back" type="submit" class="btn btn-danger" id="registerBusiness">Go Back</button>
            </form>
            <br>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"
                  enctype="multipart/form-data">
                <input name="id" style="display:none;" value=<?php echo "'";
                echo $deskService != null ? $deskService->getId() : 0;
                echo "'>"; ?>
                <?php
                if (count($sportelists) == 0 && $deskService == null) {
                    echo '<div class="alert alert-danger" role="alert" style="text-align:center; width:700px; margin:auto;">
            <h5 style="color:red; margin-top:100px; margin-bottom:100px">There are currently no free employees in your business
            to assign the task of managing a new desk service. Therefore you cannot create a desk service! In order to do so,
            please first create a a new employee account in you business or activate a free user.</h5>
        </div>';
                }

                ?>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"
                       enctype="multipart/form-data">
                <input name="id" style="display:none;" value=<?php echo "'";
                echo $deskService != null ? $deskService->getId() : 0;
                echo "'>"; ?>

                <div class="form-group">

                <label for="inputName" <?php if (count($sportelists) == 0 && $deskService == null) echo "style='display:none;'" ?>>Name</label>
                <input <?php if (count($sportelists) == 0 && $deskService == null) echo "style='display:none;'" ?>
                        name="name" type="text" class="form-control" id="inputName" value=
                <?php echo "'";
                echo $deskService != null ? $deskService->getName() : "";
                echo "'>"; ?>
                </div>
                <div class="form-group">
                    <label for="inputAddress" <?php if (count($sportelists) == 0 && $deskService == null) echo "style='display:none;'" ?>>ETC</label>
                    <input <?php if (count($sportelists) == 0 && $deskService == null) echo "style='display:none;'" ?>
                            name="etc" type="text" class="form-control" id="inputAddress" value=
                    <?php echo "'";
                    echo $deskService != null ? $deskService->getEtc() : "";
                    echo "'>"; ?>
                    </div>

                    <div class="form-group">
                        <label for="sportelists" <?php if (count($sportelists) == 0 && $deskService == null) echo "style='display:none;'" ?>>Sportelist</label>
                        <select name="sportelists" class="form-control"
                                id="sportelists" <?php if (count($sportelists) == 0 && $deskService == null) echo "style='display:none;'" ?>>
                            <?php
                            if ($deskService != null) {
                                echo "<option selected='true' value = '";
                                echo $deskService->getSportelist()->getId();
                                echo "'>" .
                                    $deskService->getSportelist()->getName() . ' ' . $deskService->getSportelist()->getSurname() .
                                    "</option>";
                            }
                            ?>
                            <?php
                            foreach ($sportelists as $sportelist) {
                                echo "<option value='";
                                echo $sportelist->getFreeEmployee()->getId();
                                echo "'>";
                                echo $sportelist->getFreeEmployee()->getName() . " " . $sportelist->getFreeEmployee()->getSurname();
                                echo "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <span <?php if (count($sportelists) == 0 && $deskService == null) echo "style='display:none;'" ?>>Turn <?php echo $deskService != null ? $deskService->getName() : "" ?>
                        off/on</span>
                    <br>
                    <!--                            <div class="form-group">-->
                    <!--                                <div class="form-check">-->
                    <label class='switch'
                           style="<?php if (count($sportelists) == 0 && $deskService == null) echo "display:none;" ?>margin-top: 5px;">
                        <input name='active' type='checkbox'
                            <?php echo $deskService != null ? (($deskService->getIsActive() == 1 ? 'checked>' : 'unchecked>')) : 'checked>'; ?>
                        >
                        <span class='slider round'></span>
                        <input id='hiddenIsActive' name='hiddenIsActive' type='hidden' value='No'>

                    </label>
                    <br>
                    <hr <?php if (count($sportelists) == 0) echo "style='display:none;'" ?>>
                    <!--                                </div>-->
                    <!--                            </div>-->


                    <button name="update" type="submit" class="btn btn-primary"
                            id="update" <?php if (($deskService == null)) echo "style='display:none;'" ?> >
                        Save Changes
                    </button>
                    <button name="save" type="submit" class="btn btn-primary"
                            id="update" <?php if ($deskService != null || count($sportelists) == 0) echo "style='display:none;'" ?> >
                        Save Changes
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
} ?>