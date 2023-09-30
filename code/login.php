<?php
require_once('head.php')
?>

<body class="bg-success">
    <div class="outer-container bg-light p-5">
        <h1 class="text-center mb-4 title"><i class="fa-solid fa-house-circle-check text-success"></i> House Wallet</h1>

        <div class="btn-group d-flex justify-content-center mb-3 switch" role="group" aria-label="Basic checkbox toggle button group">
            <button class="btn no-shadow  w-50 active login-btn">Login</button>
            <button class="btn no-shadow   w-50 signup-btn">Sign Up</button>
        </div>

        <!-- Login Form -->
        <form action="index.php" method="post" class="d-flex flex-column login-form">
            <input class="p-2 mb-2 mt-2 border-0" placeholder="Email Address" name="email">
            <input class="p-2 mb-2 mt-2 border-0" type="password" placeholder="Password" name="password">
            <div class="m-2 mb-3 text-center forgot"> </div>
            <input type="hidden" name="login" value="Yes">
            <input class="btn active btn-success border-0" type="submit" value="Login">
            <div class="m-3 mt-4 text-center">Not a member? <button class="link border-0 bg-light signup-btn link-success">Sign Up Now!</button></div>
            <?php
            if (!empty($_GET['status'])){
            if ($_GET['status'] == 'invalid_email')
                echo "<div class='text-center text-danger'>This Email is invalid. Try Again!</div>";
            else if ($_GET['status'] == 'not_found')
                echo "<div class='text-center text-danger'>Incorrect Email or Password. Try Again!</div>";
            else if ($_GET['status'] == 'empty')
                echo "<div class='text-center text-danger'>Make sure you leave no empty fields!</div>";
            }
            ?>
        </form>

        <!-- Signup Form -->
        <form action="index.php" method="post" class="d-flex flex-column signup-form d-none">
            <input class="p-2 mb-2 mt-2 border-0" placeholder="Name" name="name">
            <input class="p-2 mb-2 mt-2 border-0" placeholder="Email Address" name="email">
            <input class="p-2 mb-2 mt-2 border-0" type="password" placeholder="Password" name="password">
            <input class="p-2 mb-3 mt-2 border-0" type="password" placeholder="Confirm Password" name="confpassword">
            <input type="hidden" name="signup" value="Yes">
            <input class="btn active btn-success border-0" type="submit" value="Sign Up">
            <div class="m-2 mt-3 text-center">Already a member? <button class="link border-0 bg-light login-btn link-success">Log in!</button></div>
            <?php
            if (!empty($_GET['status'])){
            if ($_GET['status'] == 'already_exists')
                echo "<div class='text-center text-danger'>This Email is already registerd. Try Another One!</div>";
            else if ($_GET['status'] == 'dismatch')
                echo "<div class='text-center text-danger'>Password and Password Confirmation are mismatching. Try Again!</div>";
            else if ($_GET['status'] == 'empty')
                echo "<div class='text-center text-danger'>Make sure you leave no empty fields!</div>";
            else if ($_GET['status'] == 'invalid_email')
                echo "<div class='text-center text-danger'>Make sure you enter a valid email!</div>";
            }
            ?>
        </form>

    </div>

    <?php
    if (empty($_GET['signupform'])) {
        echo '<script> loginSetup(); </script>';
    } else {
        echo '<script> signupSetup(); </script>';
    }
    ?>

</body>

</html>