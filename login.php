<?php
session_start();
include 'conn.php';

$message = '';

if(isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check admin first
            // Check admin first
        $admin_query = "SELECT * FROM admin WHERE email='$email'";
        $admin_result = mysqli_query($conn, $admin_query);

        if(mysqli_num_rows($admin_result) == 1) {
            $admin = mysqli_fetch_assoc($admin_result);
            // Plain text comparison for admin
            if($password === $admin['password']) {
                $_SESSION['admin_id'] = $admin['admin_id'];
                $_SESSION['admin_name'] = $admin['username'];
                // Redirect to your localhost admin folder
                header("Location: https://blueviolet-hedgehog-858395.hostingersite.com/Admin/index.php");
                exit;
            } else {
                $message = "Incorrect password for admin.";
            }
        }else {
        // Regular user
        $user_query = "SELECT * FROM users WHERE email='$email'";
        $user_result = mysqli_query($conn, $user_query);

        if(mysqli_num_rows($user_result) == 1) {
            $user = mysqli_fetch_assoc($user_result);
            if(password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['fullname'] = $user['fullname'];
                $_SESSION['email'] = $user['email'];
                header("Location: index.php"); // Redirect user to website homepage
                exit;
            } else {
                $message = "Incorrect password.";
            }
        } else {
            $message = "Email not found. Please check your email or register first.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body style="margin:0; padding:0; font-family:Arial, sans-serif; background:#f5f5f5;">
<div style="width:100%; height:100vh; display:flex; justify-content:center; align-items:center;">
    <div style="background:#fff; padding:40px; border-radius:10px; box-shadow:0 0 20px rgba(0,0,0,0.1); width:350px;">
        <h2 style="text-align:center; margin-bottom:30px; color:#333;">Login</h2>

        <?php if($message != '') { ?>
            <p style="background:#f8d7da; color:#721c24; padding:10px; border-radius:5px; text-align:center;"><?php echo $message; ?></p>
        <?php } ?>

        <form method="POST" action="">
            <input type="email" name="email" placeholder="Email" required
                style="width:100%; padding:10px; margin-bottom:15px; border:1px solid #ccc; border-radius:5px;">
            <input type="password" name="password" placeholder="Password" required
                style="width:100%; padding:10px; margin-bottom:25px; border:1px solid #ccc; border-radius:5px;">

            <input type="submit" name="login" value="Login"
                style="width:100%; padding:12px; background:#007bff; color:#fff; border:none; border-radius:5px; cursor:pointer; font-weight:bold;">
        </form>

        <p style="text-align:center; margin-top:20px; color:#666;">
    Don't have an account? <a href="register.php" style="color:#007bff; text-decoration:none;">Register</a>
</p>
<p style="text-align:center; margin-top:5px; color:#666;">
    Forgot password? <a href="forgot.php" style="color:#007bff; text-decoration:none;">Reset</a>
</p>

<!-- Rider Login Button -->
<p style="text-align:center; margin-top:15px;">
    <a href="rider_login.php" 
       style="display:inline-block; padding:10px 20px; background:#28a745; color:#fff; border-radius:5px; text-decoration:none; font-weight:bold;">
       Rider Login
    </a>
</p>

    </div>
</div>
</body>
</html>
