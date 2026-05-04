<?php 
// Start the session to store cart items
session_start();

$hostname = "localhost"; 
$username = "ecpi_user"; 
$password = "Password1"; 
$dbname = "products"; 

$conn = mysqli_connect($hostname, $username, $password, $dbname); 

// --- ADD TO CART LOGIC ---
if (isset($_POST['add_to_cart'])) {
    // 1. Get the ID from the form
    $product_id = $_POST['product_id']; 
    $product_name = $_POST['product_name'];
    $cost = $_POST['cost'];

    // 2. Check if this specific ID already exists in the cart session
    if(isset($_SESSION['cart'][$product_id])){
        // If it exists, just increase the quantity
        $_SESSION['cart'][$product_id]['quantity'] += 1;
    } else {
        // If it's new, add it to the cart with quantity 1
        $_SESSION['cart'][$product_id] = [
            'name' => $product_name,
            'price' => $cost,
            'quantity' => 1
        ];
    }
    
    echo "<script>alert('Item added to cart!');</script>";
}

$query = "SELECT * FROM catalog"; 
$result = mysqli_query($conn, $query); 
?> 

<style> 
    table { border-spacing: 5px; width: 80%; margin: 20px auto; font-family: Arial, sans-serif; } 
    table, th, td { border: 1px solid black; border-collapse: collapse; } 
    th, td { padding: 15px; text-align: center; } 
    th { background-color: lightcyan; } 
    tr:nth-child(even) { background-color: white; } 
    tr:nth-child(odd) { background-color: lightgrey; } 
</style> 

<html> 
<head> 
    <title> Catalog Page </title> 
</head> 
<body> 
    <h2 style="text-align:center;">Catalog:</h2> 
    <table> 
        <tr style="font-size: large;"> 
            <th> Product Id</th> 
            <th> Product Name </th> 
            <th> Description</th> 
            <th> Cost</th> 
            <th> Action </th>
        </tr> 
        <?php while($row = mysqli_fetch_array($result)): ?> 
        <tr> 
            <td><?php echo $row["product_id"];?></td> 
            <td><?php echo $row["product_name"];?></td> 
            <td><?php echo $row["description"];?></td> 
            <td>$<?php echo number_format($row["cost"], 2);?></td> 
            <td>
                <form method="POST" action="">
                    <input type="hidden" name="product_id" value="<?php echo $row["product_id"]; ?>">
                    <input type="hidden" name="product_name" value="<?php echo $row["product_name"]; ?>">
                    <input type="hidden" name="cost" value="<?php echo $row["cost"]; ?>">
                    <input type="submit" name="add_to_cart" value="Add to Cart">
                </form>
            </td>
        </tr> 
        <?php endwhile; ?> 
    </table> 
    <p style="text-align:center;"><a href="cart.php">View Shopping Cart</a></p>
</body> 
</html>
