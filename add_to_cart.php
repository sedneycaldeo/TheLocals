<?php
session_start();
include "conn.php"; // Database connection

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle adding a product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    $stmt = $conn->prepare("SELECT product_name, price, image FROM products WHERE product_id=?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if ($product) {
        $quantity = 1;
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] += 1;
        } else {
            $_SESSION['cart'][$product_id] = [
                'product_name' => $product['product_name'],
                'price' => $product['price'],
                'image' => $product['image'],
                'quantity' => $quantity
            ];
        }
    }
    header("Location: add_to_cart.php");
    exit;
}

// Handle AJAX updates (quantity change)
if (isset($_POST['update_quantity']) && isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $id = $_POST['product_id'];
    $qty = max(1, intval($_POST['quantity']));
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['quantity'] = $qty;
        echo json_encode(['status'=>'success']);
    }
    exit;
}

// Remove single item
if (isset($_GET['remove'])) {
    $remove_id = $_GET['remove'];
    unset($_SESSION['cart'][$remove_id]);
    header("Location: add_to_cart.php");
    exit;
}

// Clear cart
if (isset($_POST['clear_cart'])) {
    unset($_SESSION['cart']);
    header("Location: add_to_cart.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Your Cart | The Fry Project</title>
<style>
body { margin:0; font-family:'Poppins',sans-serif; background-color:#f8f9fa; }
table { width:100%; border-collapse:collapse; }
th, td { padding:10px; border:1px solid #ddd; text-align:center; }
th { background-color:#F4C430; }
img { max-width:80px; height:auto; }
button, a.button { cursor:pointer; text-decoration:none; display:inline-block; }
input[type=number] { width:60px; padding:5px; border-radius:4px; border:1px solid #ccc; text-align:center; }
</style>
</head>
<body>

<div style="text-align:center; margin:30px;">
<h1 style="color:#ff5a5f;">Your Shopping Cart</h1>

<?php if (!empty($_SESSION['cart'])): ?>
<form method="POST" action="add_to_cart.php" style="max-width:700px; margin:auto;" id="cart-form">
    <table>
        <thead>
            <tr>
                <th>Select</th>
                <th>Product</th>
                <th>Image</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
                <th>Remove</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($_SESSION['cart'] as $id => $item): ?>
            <tr data-id="<?php echo $id; ?>">
                <td><input type="checkbox" class="select-item" name="checkout_items[]" value="<?php echo $id; ?>" checked></td>
                <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                <td>
                    <?php if(!empty($item['image'])): ?>
                        <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>">
                    <?php endif; ?>
                </td>
                <td class="price">₱<?php echo number_format($item['price'],2); ?></td>
                <td>
                    <input type="number" class="quantity" data-id="<?php echo $id; ?>" data-price="<?php echo $item['price']; ?>" value="<?php echo $item['quantity']; ?>" min="1">
                </td>
                <td class="subtotal">₱<?php echo number_format($item['price'] * $item['quantity'],2); ?></td>
                <td>
                    <a href="add_to_cart.php?remove=<?php echo $id; ?>" class="button" style="background-color:#ff5a5f; color:white; padding:5px 10px; border-radius:4px;">X</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="5" style="text-align:right; font-weight:600;">Total:</td>
                <td id="total" style="font-weight:600;">₱0.00</td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top:20px; display:flex; justify-content:center; gap:10px;">
        <a href="product.php" class="button" style="background-color:#6c757d; color:white; padding:10px 20px; border-radius:6px;">Exit</a>
        <button type="submit" name="clear_cart" style="background-color:#ff5a5f; border:none; color:white; padding:10px 20px; border-radius:6px;">Clear Cart</button>
        <button type="submit" formaction="checkout.php" style="background-color:#28a745; border:none; color:white; padding:10px 20px; border-radius:6px;">Checkout</button>
    </div>
</form>

<script>
// Update totals
function updateTotals() {
    let total = 0;
    document.querySelectorAll('tr[data-id]').forEach(row => {
        const qtyInput = row.querySelector('.quantity');
        const subtotalCell = row.querySelector('.subtotal');
        const checkbox = row.querySelector('.select-item');

        const price = parseFloat(qtyInput.dataset.price);
        const qty = parseInt(qtyInput.value);
        const subtotal = price * qty;
        subtotalCell.textContent = '₱' + subtotal.toFixed(2);

        if (checkbox.checked) total += subtotal;
    });
    document.getElementById('total').textContent = '₱' + total.toFixed(2);
}

// Save quantity to session via AJAX
document.querySelectorAll('.quantity').forEach(input => {
    input.addEventListener('input', function(){
        const id = this.dataset.id;
        const qty = this.value;
        fetch('add_to_cart.php', {
            method: 'POST',
            headers: {'Content-Type':'application/x-www-form-urlencoded'},
            body: `update_quantity=1&product_id=${id}&quantity=${qty}`
        }).then(response=>response.json()).then(updateTotals);
        updateTotals();
    });
});

// Checkbox change updates total
document.querySelectorAll('.select-item').forEach(cb => {
    cb.addEventListener('change', updateTotals);
});

// Initial calculation
updateTotals();
</script>

<?php else: ?>
<p style="color:#6c757d; font-size:16px;">Your cart is empty! <a href="index.php" style="color:#ff5a5f;">Go back to Home</a></p>
<?php endif; ?>

</div>
</body>
</html>
