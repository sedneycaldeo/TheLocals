<?php
session_start();
include 'conn.php';

$message = '';

if(isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM riders WHERE username='$username'");
    if(mysqli_num_rows($result) > 0) {
        $rider = mysqli_fetch_assoc($result);
        if(password_verify($password, $rider['password'])) {
            $_SESSION['rider_id'] = $rider['rider_id'];
            $_SESSION['rider_name'] = $rider['fullname'];
            header("Location: riders.php"); // redirect to rider dashboard
            exit();
        } else {
            $message = "Incorrect password!";
        }
    } else {
        $message = "Username not found!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Rider Login</title>
</head>
<body style="font-family:Arial, sans-serif; display:flex; justify-content:center; align-items:center; height:100vh; background:#f5f5f5;">
    <div style="background:#fff; padding:40px; border-radius:10px; box-shadow:0 0 20px rgba(0,0,0,0.1); width:350px;">
        <h2 style="text-align:center; margin-bottom:30px;">Rider Login</h2>

        <?php if($message != ''): ?>
            <p style="background:#f8d7da; color:#721c24; padding:10px; border-radius:5px; text-align:center;"><?php echo $message; ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required style="width:100%; padding:10px; margin-bottom:15px;">
            <input type="password" name="password" placeholder="Password" required style="width:100%; padding:10px; margin-bottom:20px;">
            <input type="submit" name="login" value="Login" style="width:100%; padding:12px; background:#007bff; color:#fff; border:none; cursor:pointer; font-weight:bold;">
        </form>

        <p style="text-align:center; margin-top:20px;">Don't have an account? <a href="rider_register.php">Register</a></p>
        <!-- User Login Button -->
<p style="text-align:center; margin-top:15px;">
    <a href="login.php" 
       style="display:inline-block; padding:10px 20px; background:#28a745; color:#fff; border-radius:5px; text-decoration:none; font-weight:bold;">
       User Login
    </a>
</p>

    </div>
</body>
</html>
