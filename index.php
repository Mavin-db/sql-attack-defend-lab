<?php
// index.php – simple login form
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Shop Lab Login</title>
</head>
<body>
    <h1>Shop Lab – Login</h1>
    <form method="post" action="login.php">
        <label>
            Username:
            <input type="text" name="username">
        </label><br><br>
        <label>
            Password:
           <!-- <input type="password" name="password"> -->
	      <input type="text" name="password">
        </label><br><br>
        <button type="submit">Login</button>
    </form>
</body>
</html>
