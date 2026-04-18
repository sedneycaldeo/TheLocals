<?php
session_start();
include 'conn.php'; // your database connection

$message = '';
$success_message = '';

// ✅ Show success message if redirected from register.php
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']); // remove after showing once
}

// Prefill email from session if available
$prefill_email = isset($_SESSION['email']) ? $_SESSION['email'] : '';

if (isset($_POST['verify'])) {
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $otp_input = mysqli_real_escape_string($conn, trim($_POST['otp']));

    // Check if email and OTP match
    $query = "SELECT * FROM users WHERE email='$email' AND otp='$otp_input' AND email_verified=0";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        // OTP is correct, update email_verified
        $update = "UPDATE users SET email_verified=1, otp=NULL WHERE email='$email'";
        if (mysqli_query($conn, $update)) {
            $message = "✅ Email verified successfully! Redirecting to login...";
            header("refresh:3;url=login.php");
        } else {
            $message = "❌ Verification failed. Please try again.";
        }
    } else {
        $message = "⚠️ Invalid OTP or email already verified.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email | Gugma Lokal</title>
    <style>
        body {
            margin:0; padding:0; font-family:Arial, sans-serif; background:#f5f5f5;
        }
        .container {
            width:100%; height:100vh; display:flex; justify-content:center; align-items:center;
        }
        .card {
            background:#fff; padding:40px; border-radius:10px; box-shadow:0 0 20px rgba(0,0,0,0.1); width:350px;
        }
        h2 {
            text-align:center; margin-bottom:30px; color:#333;
        }
        .message {
            background:#f8d7da; color:#721c24; padding:10px; border-radius:5px; text-align:center; margin-bottom:15px;
        }
        .success {
            background:#d4edda; color:#155724; padding:10px; border-radius:5px; text-align:center; margin-bottom:15px;
        }
        input[type=email], input[type=text] {
            width:100%; padding:10px; margin-bottom:15px; border:1px solid #ccc; border-radius:5px;
        }
        input[type=submit] {
            width:100%; padding:12px; background:#007bff; color:#fff; border:none; border-radius:5px; cursor:pointer; font-weight:bold;
        }
        input[type=submit]:hover {
            background:#0056b3;
        }
        p a {
            color:#007bff; text-decoration:none;
        }
        @media screen and (max-width:480px) {
            .card { width:90%; padding:20px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h2>Verify Email</h2>

            <?php if ($success_message != '') { ?>
                <p class="success"><?php echo $success_message; ?></p>
            <?php } ?>

            <?php if ($message != '') { ?>
                <p class="message"><?php echo $message; ?></p>
            <?php } ?>

            <form method="POST" action="">
                <input type="email" name="email" placeholder="Your Email" required 
                    value="<?php echo htmlspecialchars($prefill_email); ?>">
                <input type="text" name="otp" placeholder="Enter OTP" required>
                
                <input type="submit" name="verify" value="Verify">
            </form>

            <p style="text-align:center; margin-top:20px; color:#666;">
                Back to <a href="login.php">Login</a>
            </p>
        </div>
    </div>
</body>
</html>
