<?php
session_start();
$_SESSION = array();

$messageError = "";
if (isset($_POST["login"])) {
    $userController = new UserController();
    $username = $userController->getUserByUsernamePassword($_POST['username'], $_POST['pswd']);
    if (count($username) == 1) {

        if ($username[0]->getType()->getID() == UserType::$CUSTOMER) {
            $_SESSION["CustomerID"] = $username[0]->getId();
            header("Location: customer-home.php");
        } else if ($username[0]->getType()->getID() == UserType::$IQUEUE_ADMIN) {
            $_SESSION["iQueueAdmin"] = $username[0]->getId();
            header("Location: admin-home.php");
        } else if ($username[0]->getType()->getID() == UserType::$BUSINESS_MANAGER) {
            $_SESSION["ManagerID"] = $username[0]->getId();
            header("Location: manager-home.php");
        } else if ($username[0]->getType()->getID() == UserType::$SPORTELIST) {
            $_SESSION["SportelistID"] = $username[0]->getId();
            header("Location: sportelist-home.php");
        }
    } else {
        $messageError = "Bad Username or Password";
    }
}
if (isset($_POST["signUp"])) {
    header("Location: sign-up-customer.php");
}
?>
<html>
<head>
    <title>iQueue - Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
    <style type="text/css">
        form {
            width: 70%;
            margin: auto;
        }

        h1 {
            width: 250px;
            margin-top: 30px;
            margin-left: auto;
            margin-right: auto;
            text-align: center;
            color: white;
        }


    </style>
    <link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico" />
</head>
<body>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
    <div class="container">
        <h1>iQueue</h1>
        <div class="row">
            <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
                <div class="card card-signin my-5">
                    <div class="card-body">
                        <h5 class="card-title text-center">Sign In</h5>
                        <form class="form-signin">
                            <div class="form-label-group">
                                <input type="text" id="inputEmail" class="form-control" placeholder="Enter Username"
                                       required autofocus value="<?php
                                if (isset($_POST["username"]))
                                    echo $_POST["username"];
                                ?>" name="username" placeholder="Enter Username">
                                <label for="inputEmail">Username</label>
                            </div>

                            <div class="form-label-group">
                                <input type="password" id="inputPassword" class="form-control"
                                       placeholder="Password"
                                       required name="pswd">
                                <label for="inputPassword">Password</label>
                            </div>

                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input" id="customCheck1">
                                <p style="color: red">
                                    <?php
                                    if (isset($messageError)) {
                                        echo $messageError;
                                    }
                                    ?>
                                </p>
                            </div>
                            <button class="btn btn-lg btn-primary btn-block text-uppercase" type="submit"
                                    name="login">Sign in
                            </button>
                            <hr class="my-4">

                        </form>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"
                              enctype="multipart/form-data">
                            <button class="btn btn-lg btn-google btn-block text-uppercase" type="submit" name="signUp"><i
                                        class="fab fa-google mr-2"></i> Sign Up
                            </button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

</body>
</html>
