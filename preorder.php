<?php
session_start();
include 'conn.php';

// Ensure user is logged in
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user info
$stmt = $conn->prepare("SELECT fullname, email, phone FROM users WHERE id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Fetch all products
$productsArr = [];
$product_result = mysqli_query($conn, "SELECT product_id, product_name, price, image FROM products ORDER BY product_name ASC");
while($row = mysqli_fetch_assoc($product_result)){
    $productsArr[] = $row;
}

// Handle form submission
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $fullname = $user['fullname'];
    $email = $user['email'];
    $phone = $user['phone'];
    
    $category = $_POST['category'] ?? '';
    if($category === 'Others') {
        $category = $_POST['other_category'] ?? '';
    }
    $notes = $_POST['notes'] ?? '';

    $products = $_POST['product_name'] ?? [];
    $quantities = $_POST['quantity'] ?? [];

    if(!is_array($products)) $products = [];
    if(!is_array($quantities)) $quantities = [];

    $preorder_id = "PRE".time();
    $totalAmount = 0;

    foreach($products as $index => $product_name){
        $product_name = trim($product_name);
        if($product_name === '') continue; // skip empty

        $quantity = isset($quantities[$index]) ? (int)$quantities[$index] : 1;

        // Fetch product info from products table
        $stmt_product = $conn->prepare("SELECT product_name, price, image FROM products WHERE product_name = ?");
        $stmt_product->bind_param("s", $product_name);
        $stmt_product->execute();
        $result_product = $stmt_product->get_result();
        $productData = $result_product->fetch_assoc();

        if(!$productData) continue; // skip invalid products

        $unit_price = (float)$productData['price'];
        $subtotal = $unit_price * $quantity;
        $totalAmount += $subtotal;

        // Use image path from products table
        $savedImage = $productData['image'] ?? '';

        // Insert into preorders
        $stmt_insert = $conn->prepare("
            INSERT INTO preorders 
            (preorder_id, user_id, fullname, email, phone, category, product_name, quantity, unit_price, subtotal, image, notes, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");

        // Correct bind_param string for 12 variables
        $stmt_insert->bind_param(
            "sisssssidddss",
            $preorder_id,   // s
            $user_id,       // i
            $fullname,      // s
            $email,         // s
            $phone,         // s
            $category,      // s
            $product_name,  // s
            $quantity,      // i
            $unit_price,    // d
            $subtotal,      // d
            $totalAmount,
            $savedImage,    // s
            $notes          // s
        );

        $stmt_insert->execute();
    } // end foreach

    // Redirect after all inserts
    header("Location: po_receipt.php?preorder_id={$preorder_id}");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pre-order | The Fry Project</title>
<style>
body { font-family: Arial, sans-serif; background:#f4f4f4; margin:0; padding:20px; }
.container { max-width:700px; margin:auto; background:#fff; padding:2rem; border-radius:10px; box-shadow:0 4px 10px rgba(0,0,0,0.1); }
h2 { text-align:center; margin-bottom:1.5rem; }
label { display:block; margin-top:1rem; font-weight:bold; }
input, select, textarea, button { width:100%; padding:0.6rem; margin-top:0.3rem; border:1px solid #ccc; border-radius:6px; }
textarea { resize:vertical; }
.submit-btn { margin-top:1.5rem; background:#28a745; color:#fff; border:none; font-size:1rem; border-radius:8px; cursor:pointer; }
.submit-btn:hover { background:#218838; }
.product-row { margin-bottom:20px; padding:10px; border:1px solid #ccc; border-radius:8px; background:#fafafa; position:relative; }
.product-row.invalid { border-color:red; }
.delete-btn { position:absolute; top:10px; right:10px; background:#ff5a5f; color:white; border:none; border-radius:50%; width:25px; height:25px; cursor:pointer; font-weight:bold; }
.product-img { max-width:100%; height:auto; margin-top:5px; display:none; }
.subtotal { margin-top:5px; font-weight:bold; }
.total { font-size:1.1rem; font-weight:bold; margin-top:1rem; }
#other-category-container { display:none; margin-top:0.5rem; }
.product-wrapper { position:relative; }
.suggestions { border:1px solid #ccc; border-top:none; max-height:150px; overflow-y:auto; display:none; position:absolute; background:white; z-index:10; width:100%; }
.suggestions div { padding:0.5rem; cursor:pointer; }
.suggestions div:hover { background:#f4f4f4; }
</style>
</head>
<body>

<div class="container">
<h2>Pre-order Form</h2>
<form method="POST">
<label>Full Name</label>
<input type="text" name="fullname" value="<?php echo htmlspecialchars($user['fullname']); ?>" readonly>

<label>Email</label>
<input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>

<label>Phone</label>
<input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" readonly>

<label>Category</label>
<select name="category" onchange="checkCategory(this)" required>
<option value="">Select Category</option>
<option value="Pasalubong Center">Pasalubong Center</option>
<option value="Others">Others</option>
</select>

<div id="other-category-container">
<label>Please specify</label>
<input type="text" name="other_category" placeholder="Enter category">
</div>

<div id="products-container"></div>

<button type="button" onclick="addProductRow()" style="margin-top:10px; padding:0.6rem 1rem; border:none; background:#007bff; color:white; border-radius:6px; cursor:pointer;">Add Product</button>

<p class="total">Total: ₱0.00</p>
<label>Additional Notes</label>
<textarea name="notes" rows="3" placeholder="Any special instructions (optional)"></textarea>

<button type="submit" class="submit-btn">Place Pre-order</button>
</form>
</div>

<script>
const allProducts = <?php echo json_encode(array_map(fn($p)=>['name'=>$p['product_name'],'image'=>$p['image'],'price'=>$p['price']], $productsArr)); ?>;

function addProductRow(){
    const container = document.getElementById('products-container');
    const row = document.createElement('div');
    row.className = 'product-row';
    row.innerHTML = `
        <button type="button" class="delete-btn" onclick="deleteRow(this)">×</button>
        <label>Product Name</label>
        <div class="product-wrapper">
            <input type="text" name="product_name[]" oninput="searchProduct(this); calculateTotal();" placeholder="Search product..." autocomplete="off">
            <div class="suggestions"></div>
        </div>
        <img src="" class="product-img" alt="Product Image">
        <label>Quantity</label>
        <input type="number" name="quantity[]" min="1" value="1" oninput="calculateTotal()">
        <p class="subtotal">Subtotal: ₱0.00</p>
    `;
    container.appendChild(row);
}

function deleteRow(btn){
    btn.closest('.product-row').remove();
    calculateTotal();
}

function checkCategory(select){
    const otherInput = document.getElementById('other-category-container');
    otherInput.style.display = (select.value === 'Others') ? 'block' : 'none';
}

function searchProduct(input){
    const val = input.value.toLowerCase();
    const suggestionsBox = input.nextElementSibling;
    suggestionsBox.innerHTML = '';
    if(val === ''){ 
        suggestionsBox.style.display = 'none'; 
        calculateTotal();
        return; 
    }

    const filtered = allProducts.filter(p => p.name.toLowerCase().includes(val));
    filtered.forEach(p => {
        const div = document.createElement('div');
        div.textContent = p.name;
        div.onclick = () => {
            input.value = p.name;
            const img = input.closest('.product-row').querySelector('.product-img');
            img.src = p.image || '';
            img.style.display = p.image ? 'block' : 'none';
            suggestionsBox.style.display = 'none';
            calculateTotal();
        }
        suggestionsBox.appendChild(div);
    });

    suggestionsBox.style.display = filtered.length > 0 ? 'block' : 'none';
}

document.addEventListener('click', function(e){
    if(!e.target.closest('.product-wrapper')){
        document.querySelectorAll('.suggestions').forEach(el => el.style.display='none');
    }
});

function calculateTotal(){
    let total = 0;
    document.querySelectorAll('.product-row').forEach(row => {
        const qty = parseFloat(row.querySelector('input[name="quantity[]"]').value) || 0;
        const inputVal = row.querySelector('input[name="product_name[]"]').value.trim();
        const product = allProducts.find(p => p.name === inputVal);
        const subtotalElem = row.querySelector('.subtotal');

        if(product){
            row.classList.remove('invalid');
            const price = parseFloat(product.price);
            const subtotal = qty * price;
            subtotalElem.textContent = `Subtotal: ₱${subtotal.toFixed(2)}`;
            total += subtotal;
        } else {
            row.classList.add('invalid');
            subtotalElem.textContent = `Invalid product`;
        }
    });
    document.querySelector('.total').textContent = `Total: ₱${total.toFixed(2)}`;
}

document.querySelector('form').addEventListener('submit', function(e){
    const invalid = Array.from(document.querySelectorAll('.product-row')).some(row => {
        const inputVal = row.querySelector('input[name="product_name[]"]').value.trim();
        return inputVal !== '' && !allProducts.find(p => p.name === inputVal);
    });
    if(invalid){
        e.preventDefault();
        alert('Please fix invalid product names highlighted in red.');
    }
});

// Initialize first product row
addProductRow();
</script>

</body>
</html>
