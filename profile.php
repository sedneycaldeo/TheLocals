<?php
session_start();
include 'conn.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle profile picture upload
if(isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0){
    $uploadDir = 'profilepics/';
    if(!is_dir($uploadDir)){
        mkdir($uploadDir, 0777, true);
    }
    $fileName = basename($_FILES['profile_pic']['name']);
    $targetFile = $uploadDir . $fileName;
    if(move_uploaded_file($_FILES['profile_pic']['tmp_name'], $targetFile)){
        $update = "UPDATE users SET profile_pic='$targetFile' WHERE id='$user_id'";
        mysqli_query($conn, $update);
        header("Location: profile.php");
        exit;
    }
}

// Fetch user details
$user_query = "SELECT * FROM users WHERE id='$user_id'";
$user_result = mysqli_query($conn, $user_query);
$user = mysqli_fetch_assoc($user_result);

// Fetch Order History
$order_query = "SELECT * FROM orders WHERE user_id='$user_id' ORDER BY order_date DESC";
$order_result = mysqli_query($conn, $order_query);

// Fetch Preorder History
$preorder_query = "SELECT * FROM preorders WHERE user_id='$user_id' ORDER BY created_at DESC";
$preorder_result = mysqli_query($conn, $preorder_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Profile | The Fry Project</title>
<style>
* { box-sizing: border-box; font-family:'Poppins', sans-serif; }
body { margin:0; background-color:#f8f9fa; }
.container { max-width:1000px; margin:auto; padding:40px 20px; }

.profile-card {
    background:#fff; padding:30px; border-radius:10px;
    box-shadow:0 4px 15px rgba(0,0,0,0.1);
    text-align:center; margin-bottom:40px;
}
.profile-pic {
    width:100px; height:100px; border-radius:50%; border:3px solid #ff5a5f;
    object-fit:cover; cursor:pointer;
}
.buttons { display:flex; justify-content:center; gap:10px; margin-top:20px; flex-wrap:wrap; }
.buttons a, .buttons button {
    text-decoration:none; background-color:#F4C430; color:black; padding:10px 20px;
    border-radius:6px; font-weight:500; transition:all 0.3s ease; border:none; cursor:pointer;
}
.buttons a:hover { background-color:#ff5a5f; color:white; }

h2.section-title { margin-bottom:15px; color:#333; }
table {
    width:100%; border-collapse:collapse; margin-bottom:30px; background:#fff;
    box-shadow:0 2px 8px rgba(0,0,0,0.1);
}
th, td { padding:12px; border-bottom:1px solid #ddd; text-align:left; }
th { background-color:#F4C430; color:#000; }
tr:hover { background-color:#f1f1f1; }
img { max-width:80px; height:auto; border-radius:8px; }
@media(max-width:700px){
    table { font-size:14px; }
    th, td { padding:8px; }
}
.profile { display:block; margin:auto; width:80px; height:80px; border-radius:50%; object-fit:cover; margin-bottom:15px; }
</style>
</head>
<body>

<div class="container">

    <!-- Profile Section -->
    <div class="profile-card">
        <form method="post" enctype="multipart/form-data">
            <label for="profile_pic">
                <img src="<?php echo !empty($user['profile_pic']) ? $user['profile_pic'] : 'https://cdn-icons-png.flaticon.com/512/149/149071.png'; ?>" 
                     alt="Profile Icon" class="profile-pic">
            </label>
            <input type="file" name="profile_pic" id="profile_pic" style="display:none;" onchange="this.form.submit()">
        </form>

        <h2><?php echo htmlspecialchars($user['fullname']); ?></h2>
        <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
        <p>Phone: <?php echo htmlspecialchars($user['phone'] ?? 'N/A'); ?></p>
        <p>Address: <?php echo htmlspecialchars($user['address'] ?? 'N/A'); ?></p>

        <div class="buttons">
            <a href="index.php">Back to Home</a>
            <a href="update_profile.php" style="background-color:#007bff; color:white;">Update Profile</a>
            <a href="logout.php" style="background-color:#ff5a5f; color:white;">Logout</a>
        </div>
    </div>

    <!-- Order History Section -->
    <div class="order-history">
        <h2 class="section-title">🛒 Order History</h2>
        <?php if(mysqli_num_rows($order_result) > 0): ?>
            <table>
                <tr>
                    <th>Order ID</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Receipt</th>
                </tr>
                <?php while($row = mysqli_fetch_assoc($order_result)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['order_id']); ?></td>
                        <td>₱<?php echo number_format($row['total'], 2); ?></td>
                        <td><?php echo htmlspecialchars($row['payment_method']); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                        <td><?php echo htmlspecialchars($row['order_date']); ?></td>
                        <td><a href="order_receipt.php?order_id=<?php echo urlencode($row['order_id']); ?>">View</a></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p style="color:#666;">No orders yet.</p>
        <?php endif; ?>
    </div>

    <!-- Preorder History Section -->
    <div class="preorder-history">
        <h2 class="section-title">📦 Preorder History</h2>
        <?php if(mysqli_num_rows($preorder_result) > 0): ?>
            <table>
                <tr>
                    <th>Preorder ID</th>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Qty</th>
                    <th>Total</th>
                    <th>Date</th>
                </tr>
                <?php while($pre = mysqli_fetch_assoc($preorder_result)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($pre['preorder_id']); ?></td>
                        <td><?php echo htmlspecialchars($pre['product_name']); ?></td>
                        <td><?php echo htmlspecialchars($pre['category']); ?></td>
                        <td><?php echo htmlspecialchars($pre['quantity']); ?></td>
                        <td>₱<?php echo number_format($pre['total'],2); ?></td>
                        <td><?php echo htmlspecialchars($pre['created_at']); ?></td>
                        <td><?php echo htmlspecialchars($pre['status']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p style="color:#666;">No preorders yet.</p>
        <?php endif; ?>
    </div>

</div>

</body>
</html>
