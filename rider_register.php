<?php
session_start();
include 'conn.php'; // database connection

$message = '';

if(isset($_POST['register'])) {

    // Get and sanitize form values
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $vehicle_type = mysqli_real_escape_string($conn, $_POST['vehicle_type']);
    $vehicle_number = mysqli_real_escape_string($conn, $_POST['vehicle_number']);
    $created_at = date('Y-m-d H:i:s');

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if email or username already exists
    $check = mysqli_query($conn, "SELECT * FROM riders WHERE email='$email' OR username='$username'");
    if(mysqli_num_rows($check) > 0){
        $message = "Email or Username already registered!";
    } else {
        // Insert new rider
        $sql = "INSERT INTO riders (fullname, email, phone, username, password, vehicle_type, vehicle_number, created_at) 
                VALUES ('$fullname', '$email', '$phone', '$username', '$hashed_password', '$vehicle_type', '$vehicle_number', '$created_at')";

        if(mysqli_query($conn, $sql)) {
            $message = "Registration successful! Please login.";
            header("Location: rider_login.php"); // Redirect to rider login
            exit();
        } else {
            $message = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Rider Registration</title>
</head>
<body style="font-family:Arial, sans-serif; background:#f5f5f5; display:flex; justify-content:center; align-items:center; height:100vh;">
    <div style="background:#fff; padding:40px; border-radius:10px; box-shadow:0 0 20px rgba(0,0,0,0.1); width:400px;">
        <h2 style="text-align:center; margin-bottom:30px;">Rider Register</h2>

        <?php if($message != ''): ?>
            <p style="background:#f8d7da; color:#721c24; padding:10px; border-radius:5px; text-align:center;"><?php echo $message; ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="text" name="fullname" placeholder="Full Name" required style="width:100%; padding:10px; margin-bottom:15px;">
            <input type="email" name="email" placeholder="Email" required style="width:100%; padding:10px; margin-bottom:15px;">
            <input type="text" name="phone" placeholder="Phone" required style="width:100%; padding:10px; margin-bottom:15px;">
            <input type="text" name="username" placeholder="Username" required style="width:100%; padding:10px; margin-bottom:15px;">
            <input type="password" name="password" placeholder="Password" required style="width:100%; padding:10px; margin-bottom:15px;">
            <input type="text" name="vehicle_type" placeholder="Vehicle Type" style="width:100%; padding:10px; margin-bottom:15px;">
            <input type="text" name="vehicle_number" placeholder="Vehicle Number" style="width:100%; padding:10px; margin-bottom:20px;">
            <input type="submit" name="register" value="Register" style="width:100%; padding:12px; background:#28a745; color:#fff; border:none; cursor:pointer; font-weight:bold;">
        </form>

        <p style="text-align:center; margin-top:20px;">Already have an account? <a href="rider_login.php">Login</a></p>
    </div>
</body>
</html>
