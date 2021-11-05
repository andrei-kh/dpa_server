<?php
require_once "php/include/utils.inc.php";
?>

<?php
$message = "";
if (get_session("error")) {
    switch (get_session("error")) {
        case "invalid_token":
            $message = "Invalid email verification token.";
            break;
        case "db_error":
            $message = "There are some problems.<br> Please try again later.";
            break;
    }
    unset_session('error');
}
?>

<!DOCTYPE html>
<html>

<head>
	<title>Index page</title>
	<link rel="stylesheet" href="css/style.css" />
</head>

<body>
	<div class="index_wrapper">
		<h1>
			<?php
			if (get_session("id")) {
				if (get_session("email_status") === "approved") {
					echo "<p><span class='hello'>hello</span> <span class='user'>" . $_SESSION["name"] . "</span></p>";
					echo "<a class='info_text' href=php/logout_script.php>logout</a>";
				} else {
					echo "</p><a class='login_text'>Please verify your email.</a></p>";
					echo "<div class='invalid-feedback'>" . $message . "</div>";
					echo "<a class='info_text' href=php/logout_script.php>logout</a>";
				}
			} else {
				echo "<p><a class='login_text' href='login.php'>Login here!</a></p>";
				echo "<a class='info_text' href='info.php'>info</a>";
			} ?>
		</h1>
	</div>
</body>

</html>