<?php
session_start();
include "conn.php";

// ✅ Require login
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

// Fetch current user data
$stmt = $conn->prepare("SELECT id, fullname, email, birthday, address, phone FROM user WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("User not found.");
}

$user = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = $_POST['fullname'];
    $new_email = $_POST['email'];
    $password = $_POST['password']; // optional: if empty, don't change
    $birthday = $_POST['birthday'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];

    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE user SET fullname=?, email=?, password=?, birthday=?, address=?, phone=? WHERE id=?");
        $stmt->bind_param("ssssssi", $fullname, $new_email, $hashedPassword, $birthday, $address, $phone, $user['id']);
    } else {
        $stmt = $conn->prepare("UPDATE user SET fullname=?, email=?, birthday=?, address=?, phone=? WHERE id=?");
        $stmt->bind_param("sssssi", $fullname, $new_email, $birthday, $address, $phone, $user['id']);
    }

   if ($stmt->execute()) {
    $_SESSION['email'] = $new_email; // update session if email changed
    // Alert and redirect using JS
    echo "<script>
            alert('Profile updated successfully!');
            window.location.href='profile.php';
          </script>";
    exit(); // Stop further PHP execution
} else {
    $error = "Failed to update profile. Please try again.";
}

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Profile</title>
<style>
    body { font-family: Arial, sans-serif; background-color: #e0e0e0; padding: 20px; }
    .container { max-width: 500px; margin: auto; background: white; padding: 20px; border-radius: 10px; }
    input { width: 100%; padding: 10px; margin: 10px 0; border-radius: 6px; border: 1px solid #ccc; }
    button { background-color: #F4C430; color: black; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; }
    button:hover { background-color: #ff5a5f; color: white; }
    .message { margin: 10px 0; font-weight: bold; }
</style>
</head>
<body>

<div class="container">
    <h2>Edit Profile</h2>

    <?php if(isset($success)) echo "<p class='message' style='color:green;'>$success</p>"; ?>
    <?php if(isset($error)) echo "<p class='message' style='color:red;'>$error</p>"; ?>

    <form method="POST">
        <label>Full Name</label>
        <input type="text" name="fullname" value="<?= htmlspecialchars($user['fullname']) ?>" required>

        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

        <label>Password (leave blank to keep current)</label>
        <input type="password" name="password">

        <label>Birthday</label>
        <input type="date" name="birthday" value="<?= htmlspecialchars($user['birthday']) ?>">

        <label>Address</label>
        <input type="text" name="address" value="<?= htmlspecialchars($user['address']) ?>">

        <label>Phone</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>">

        <button type="submit">Update Profile</button>
    </form>
</div>

</body>
</html>
