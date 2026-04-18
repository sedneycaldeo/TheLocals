<?php
session_start();
include 'conn.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch current user data
$stmt = $conn->prepare("SELECT fullname, email, phone, address, city, postal_code, birthday FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle form submission
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $fullname = $_POST['fullname'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $postal_code = $_POST['postal_code'];
    $birthday = $_POST['birthday'];

    $stmt_update = $conn->prepare("UPDATE users SET fullname=?, phone=?, address=?, city=?, postal_code=?, birthday=? WHERE id=?");
    $stmt_update->bind_param("ssssssi", $fullname, $phone, $address, $city, $postal_code, $birthday, $user_id);

    if($stmt_update->execute()){
        // Update session info
        $_SESSION['fullname'] = $fullname;
        // Redirect to profile page
        header("Location: profile.php");
        exit;
    } else {
        $message = "Failed to update profile. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Update Profile | The Fry Project</title>
<style>
body { font-family: 'Poppins', sans-serif; background: #f8f9fa; padding: 2rem; }
.container { max-width: 500px; margin: auto; background: #fff; padding: 2rem; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
h2 { text-align: center; margin-bottom: 1.5rem; color: #333; }
label { display: block; margin-top: 1rem; font-weight: 500; }
input { width: 100%; padding: 0.6rem; margin-top: 0.3rem; border: 1px solid #ccc; border-radius: 6px; }
button { margin-top: 1.5rem; width: 100%; padding: 0.8rem; background: #007bff; border: none; color: #fff; font-weight: 500; border-radius: 6px; cursor: pointer; transition: 0.3s; }
button:hover { background: #0056b3; }
.back-btn { margin-top: 10px; display: block; text-align:center; text-decoration:none; background:#6c757d; color:white; padding:10px; border-radius:6px; font-weight:500; transition:0.3s;}
.back-btn:hover { background:#5a6268; }
.message { text-align:center; margin-bottom:1rem; color:red; }
</style>
</head>
<body>

<div class="container">
    <h2>Update Profile</h2>
    
    <?php if(!empty($message)) echo "<p class='message'>{$message}</p>"; ?>

    <form method="POST">
        <label>Full Name</label>
        <input type="text" name="fullname" value="<?php echo htmlspecialchars($user['fullname']); ?>" required>

        <label>Email (cannot change)</label>
        <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>

        <label>Phone</label>
        <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">

        <label>Address</label>
        <input type="text" name="address" value="<?php echo htmlspecialchars($user['address']); ?>">

        <label>City</label>
        <input type="text" name="city" value="<?php echo htmlspecialchars($user['city']); ?>">

        <label>Postal Code</label>
        <input type="text" name="postal_code" value="<?php echo htmlspecialchars($user['postal_code']); ?>">

        <label>Birthday</label>
        <input type="date" name="birthday" value="<?php echo htmlspecialchars($user['birthday']); ?>">

        <button type="submit">Update Profile</button>
    </form>

    <a href="profile.php" class="back-btn">Back to Profile</a>
</div>

</body>
</html>
