<?php
require_once "php/include/utils.inc.php";
if (get_session("id"))
    errorMessage("index.php");
?>

<?php
$username_valid = $password_valid = $name_valid = "";
$username_error = $password_error = $name_error = $stmt_error = "";
if (get_session("error")) {
    switch (get_session("error")) {
        case "empty_input":
            $username_valid = $password_valid = $name_valid = $email_valid = "is-invalid";
            $username_error = $password_error = $name_error = $email_error = "Fields can't be empty.";
            break;
        case "invalid_username":
            $username_valid = "is-invalid";
            $username_error = "Invalid username. Usernames can only contain letters, numbers and underscores.";
            break;
        case "different_passwords":
            $password_valid = "is-invalid";
            $password_error = "Passwords don't match.";
            break;
        case "username_exists":
            $username_valid = "is-invalid";
            $username_error = "User with that username already exists.";
            break;
        case "invalid_email":
            $email_valid = "is-invalid";
            $email_error = "Invalid e-mail address.";
            break;
        case "email_exists":
            $email_valid = "is-invalid";
            $email_error = "User with that email already exists.";
            break;
        case "invalid_captcha":
            $captcha_valid = "is-invalid";
            $captcha_error = "Captcha is incorrect.";
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
    <title>Register</title>

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="register_wrapper">
        <h2>Register</h2>
        <p>Please fill in your credentials to login.</p>
        <form action="php/register_script.php" method="POST" autocomplete="off">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control <?php echo $username_valid; ?>">
                <div class="invalid-feedback"><?php echo $username_error; ?></div>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo $password_valid; ?>">
                <div class="invalid-feedback"><?php echo $password_error; ?></div>
            </div>
            <div class="form-group">
                <label>Password confirmation</label>
                <input type="password" name="password_conf" class="form-control <?php echo $password_valid; ?>">
                <div class="invalid-feedback"><?php echo $password_error; ?></div>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control <?php echo $email_valid; ?>">
                <div class="invalid-feedback"><?php echo $email_error; ?></div>
            </div>
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" class="form-control <?php echo $name_valid; ?>">
                <div class="invalid-feedback"><?php echo $name_error; ?></div>
            </div>
            <div class="form-group form-no-label">
                <div class="row">
                    <div class="col">
                        <img src="php/captcha_image.php">
                    </div>
                    <div class="col-8 align-items-center">
                        <div class="form-floating">
                            <input type="text" name="captcha" class="form-control <?php echo $captcha_valid; ?>" placeholder="Captcha">
                            <label for="captcha">Captcha</label>
                            <div class="invalid-feedback"><?php echo $captcha_error; ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group form-no-label">
                <button type="submit" class="btn btn-secondary" name="submit">Register</button>
            </div>
            <div class="form-group form-no-label">
                Have an account? <a href="login.php">Log in</a>.
                <p class="text-warning"><?php echo $stmt_error; ?></p>
            </div>
        </form>
    </div>
</body>

</html>