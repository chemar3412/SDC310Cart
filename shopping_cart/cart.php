<?php
session_start();

// 1. HANDLE QUANTITY UPDATES & REMOVAL
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = $_GET['id'];

    if ($_GET['action'] == "increase") {
        $_SESSION['cart'][$id]['quantity'] += 1;
    }

    if ($_GET['action'] == "decrease") {
        $_SESSION['cart'][$id]['quantity'] -= 1;
        if ($_SESSION['cart'][$id]['quantity'] < 1) {
            unset($_SESSION['cart'][$id]);
        }
    }

    if ($_GET['action'] == "delete") {
        unset($_SESSION['cart'][$id]);
    }

    header("Location: cart.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 70%; margin: 50px auto; border-collapse: collapse; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: center; }
        th { background-color: #f8f8f8; }
        .btn { text-decoration: none; padding: 2px 8px; border: 1px solid #ccc; background: #eee; color: #333; margin: 0 5px; border-radius: 3px; }
        .total-label { text-align: right; font-weight: bold; }
        .grand-total { font-weight: bold; font-size: 1.2em; background-color: #f0f0f0; }
        .empty-msg { text-align: center; margin-top: 50px; color: #666; }
    </style>
</head>
<body>

<h2 style="text-align:center;">Your Shopping Cart</h2>

<?php if (!empty($_SESSION['cart'])): ?>
    <table>
        <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Subtotal</th>
            <th>Remove</th>
        </tr>

        <?php
        $items_total = 0; // This is the pre-tax total
        foreach ($_SESSION['cart'] as $id => $item): 
            $line_subtotal = $item['price'] * $item['quantity'];
            $items_total += $line_subtotal;
        ?>
        <tr>
            <td><?php echo htmlspecialchars($item['name']); ?></td>
            <td>$<?php echo number_format($item['price'], 2); ?></td>
            <td>
                <a class="btn" href="cart.php?action=decrease&id=<?php echo $id; ?>">-</a>
                <strong><?php echo $item['quantity']; ?></strong>
                <a class="btn" href="cart.php?action=increase&id=<?php echo $id; ?>">+</a>
            </td>
            <td>$<?php echo number_format($line_subtotal, 2); ?></td>
            <td><a href="cart.php?action=delete&id=<?php echo $id; ?>" style="color:red; text-decoration:none; font-weight:bold;">&times; Remove</a></td>
        </tr>
        <?php endforeach; ?>

        <?php
            // Perform calculations based on the total of all items
            $tax = $items_total * 0.05;
            $shipping = $items_total * 0.10;
            $order_total = $items_total + $tax + $shipping;
        ?>

        <!-- Summary Section -->
        <tr>
            <td colspan="3" class="total-label">Items Total:</td>
            <td colspan="2">$<?php echo number_format($items_total, 2); ?></td>
        </tr>
        <tr>
            <td colspan="3" class="total-label">Tax (5%):</td>
            <td colspan="2">$<?php echo number_format($tax, 2); ?></td>
        </tr>
        <tr>
            <td colspan="3" class="total-label">Shipping (10%):</td>
            <td colspan="2">$<?php echo number_format($shipping, 2); ?></td>
        </tr>
        <tr class="grand-total">
            <td colspan="3" class="total-label">Order Total:</td>
            <td colspan="2">$<?php echo number_format($order_total, 2); ?></td>
        </tr>
    </table>

    <div style="text-align:center;">
        <a href="catalog.php">Return to Catalog</a> | 
        <a href="checkout.php" style="color:green; font-weight:bold;">Proceed to Checkout</a>
    </div>

<?php else: ?>
    <div class="empty-msg">
        <h3>Your cart is currently empty!</h3>
        <a href="catalog.php">Go back to the Catalog to add items.</a>
    </div>
<?php endif; ?>

</body>
</html>
