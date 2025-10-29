<?php
echo "<h1>Login Page</h1>";

if ($_POST) {
    echo "<p>Login form submitted!</p>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
} else {
    echo '
    <form method="POST">
        <input type="email" name="email" placeholder="Email" required><br><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <button type="submit">Login</button>
    </form>
    <a href="signup.php">Sign Up</a> | 
    <a href="index.php">Back to Home</a>
    ';
}
?>