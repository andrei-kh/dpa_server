<?php
require_once "php/include/utils.inc.php";
if (get_session("id"))
    errorMessage("index.php");
?>

<?php
$username_valid = $password_valid = "";
$username_error = $password_error = $stmt_error = "";
if (get_session("error")) {
    switch (get_session("error")) {
        case "empty_input":
            $username_valid = $password_valid = $name_valid = "is-invalid";
            $username_error = $password_error = $name_error = "Fields can't be empty.";
            break;
        case "invalid_username":
            $username_valid = "is-invalid";
            $username_error = "Invalid username. Usernames can only contain letters, numbers and underscores.";
            break;
        case "wrong_username":
            $username_valid = "is-invalid";
            $username_error = "Wrong username. Try again.";
            break;
        case "wrong_password":
            $password_valid = "is-invalid";
            $password_error = "Wrong password. Try again.";
            break;
        case "db_error":
            $stmt_error = "There are some problems.<br> Please try again later.";
            break;
    }
    unset_session('error');
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Login</title>

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="login_wrapper">
        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>
        <form action="php/login_script.php" method="POST" autocomplete="off">
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
            <div class="form-group form-no-label">
                <button type="submit" class="btn btn-secondary" name="submit">Login</button>
            </div>
            <div class="form-group form-no-label">
                <button type="submit" class="btn btn-secondary" name="submit-injection">Login Injection</button>
            </div>
            <div class="form-group form-no-label">
                Don't have an account? <a href="register.php">Register now</a>.
                <p class="text-warning"><?php echo $stmt_error; ?></p>
            </div>
        </form>
    </div>
</body>

</html>