<?php
session_start();
include 'conn.php'; // your database connection

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer classes
require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

$message = '';

if(isset($_POST['register'])) {

    // Get form values and sanitize
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $birthday = mysqli_real_escape_string($conn, $_POST['birthday']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $created_at = date('Y-m-d H:i:s');

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists
    $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if(mysqli_num_rows($check) > 0){
        $message = "Email already registered!";
    } else {

        // Generate 6-digit OTP
        $otp = rand(100000, 999999);

        // Insert user with OTP and email_verified = 0
        $sql = "INSERT INTO users (fullname, email, password, birthday, address, phone, created_at, otp, email_verified)
                VALUES ('$fullname', '$email', '$hashed_password', '$birthday', '$address', '$phone', '$created_at', '$otp', 0)";

        if(mysqli_query($conn, $sql)) {

            // Send OTP email using Hostinger SMTP
            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.hostinger.com';             // Hostinger SMTP
                $mail->SMTPAuth = true;
                $mail->Username = 'gugmalokal@gugmalokal.com';  // your mailbox
                $mail->Password = 'hK-h3rb0p173';               // your mailbox password
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->setFrom('gugmalokal@gugmalokal.com', 'Gugma Lokal');
                $mail->addAddress($email, $fullname);

                $mail->isHTML(true);
                $mail->Subject = 'Verify Your Email';
                $mail->Body    = "Hi $fullname,<br><br>Your OTP code is: <b>$otp</b><br>Please enter this code on the verification page to activate your account.<br><br>Best regards,<br><b>Gugma Lokal</b>";

                $mail->send();

                // ✅ Save email in session and redirect to verify.php
                $_SESSION['email'] = $email;
                header("Location: verify.php");
                exit();

            } catch (Exception $e) {
                $message = "Registration successful! But OTP email could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }

        } else {
            $message = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body style="margin:0; padding:0; font-family:Arial, sans-serif; background:#f5f5f5;">
    <div style="width:100%; height:100vh; display:flex; justify-content:center; align-items:center;">
        <div style="background:#fff; padding:40px; border-radius:10px; box-shadow:0 0 20px rgba(0,0,0,0.1); width:350px;">
            <h2 style="text-align:center; margin-bottom:30px; color:#333;">Register</h2>
            
            <?php if($message != '') { ?>
                <p style="background:#f8d7da; color:#721c24; padding:10px; border-radius:5px; text-align:center;"><?php echo $message; ?></p>
            <?php } ?>

            <form method="POST" action="">
                <input type="text" name="fullname" placeholder="Full Name" required 
                    style="width:100%; padding:10px; margin-bottom:15px; border:1px solid #ccc; border-radius:5px;">
                <input type="email" name="email" placeholder="Email" required 
                    style="width:100%; padding:10px; margin-bottom:15px; border:1px solid #ccc; border-radius:5px;">
                <input type="password" name="password" placeholder="Password" required 
                    style="width:100%; padding:10px; margin-bottom:15px; border:1px solid #ccc; border-radius:5px;">
                <input type="date" name="birthday" placeholder="Birthday" 
                    style="width:100%; padding:10px; margin-bottom:15px; border:1px solid #ccc; border-radius:5px;">
                <input type="text" name="address" placeholder="Address" 
                    style="width:100%; padding:10px; margin-bottom:15px; border:1px solid #ccc; border-radius:5px;">
                <input type="text" name="phone" placeholder="Phone" 
                    style="width:100%; padding:10px; margin-bottom:25px; border:1px solid #ccc; border-radius:5px;">
                
                <input type="submit" name="register" value="Register" 
                    style="width:100%; padding:12px; background:#28a745; color:#fff; border:none; border-radius:5px; cursor:pointer; font-weight:bold;">
            </form>

            <p style="text-align:center; margin-top:20px; color:#666;">
                Already have an account? <a href="login.php" style="color:#007bff; text-decoration:none;">Login</a>
            </p>
            <p style="text-align:center; margin-top:15px; color:#666;">
    Are you a rider? <a href="rider_register.php" 
        style="color:#28a745; text-decoration:none; font-weight:bold;">Register here</a>
</p>

        </div>
    </div>
</body>
</html>
