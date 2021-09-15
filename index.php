<?php
session_start();
?>

<!DOCTYPE html>
<html>

<head>
    <title>index page</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div>
        <h1>
            <?php
            if (isset($_SESSION["id"])) {
                echo "<p><span class='hello'>hello</span> <span class='user'>"
                    . $_SESSION["name"] . "</span></p> ";
                echo "<a class='info_text' href=php/logout_script.php>logout</a>";
            } else {
                echo "<p><a class='login_text' href='login.php'>Login here!</a></p>";
                echo "<a class='info_text' href='info.php'>info</a>";
            }
            ?>
        </h1>
    </div>
</body>

</html>