<?php
require_once "php/include/utils.inc.php";
session_start();
if (isset($_SESSION["id"]))
    errorMessage("index.php");
?>

<?php
$username_valid = $password_valid = "";
$username_error = $password_error = $stmt_error = "";
if (isset($_GET["error"])) {
    if ($_GET["error"] == "empty_input") {
        $username_valid = $password_valid = $name_valid = "is-invalid";
        $username_error = $password_error = $name_error = "Fields can't be empty.";
    } elseif ($_GET["error"] == "invalid_username") {
        $username_valid = "is-invalid";
        $username_error = "Invalid username. Usernames can only contain letters, numbers and underscores.";
    } elseif ($_GET["error"] == "wrong_username") {
        $username_valid = "is-invalid";
        $username_error = "Wrong username. Try again.";
    } elseif ($_GET["error"] == "wrong_password") {
        $password_valid = "is-invalid";
        $password_error = "Wrong password. Try again.";
    } elseif ($_GET["error"] == "stmt_prepare_error" || $_GET["error"] == "stmt_execute_error") {
        $stmt_error = "There are some problems.<br> Please try again later.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        .wrapper {
            font-family: "mononoki NF";
            position: absolute;
            top: 45%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 420px;
            padding: 20px;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>
        <form action="php/login_script.php" method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control <?php echo $username_valid ?>">
                <div class="invalid-feedback"><?php echo $username_error ?></div>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo $password_valid ?>">
                <div class="invalid-feedback"><?php echo $password_error ?></div>
            </div>
            <p>
            <div class="form-group">
                <button type="submit" class="btn btn-secondary" name="submit">Login</button>
            </div>
            </p>
            <p>Don't have an account? <a href="register.php">Register now</a>.</p>
        </form>
        <p class="text-warning"><?php echo $stmt_error; ?></p>
    </div>
</body>

</html>