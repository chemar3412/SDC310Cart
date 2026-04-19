<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

session_start();

// Initialize cart in session if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$request_method = $_SERVER['REQUEST_METHOD'];

// Handle different request types
switch ($request_method) {
    case 'GET':
        // Get cart contents
        echo json_encode($_SESSION['cart']);
        break;

    case 'POST':
        // Add or update product in cart
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (isset($input['action'])) {
            if ($input['action'] === 'add') {
                addToCart($input);
            } elseif ($input['action'] === 'remove') {
                removeFromCart($input['id']);
            } elseif ($input['action'] === 'update') {
                updateQuantity($input['id'], $input['quantity']);
            } elseif ($input['action'] === 'clear') {
                clearCart();
            }
        }
        
        echo json_encode([
            'success' => true,
            'cart' => $_SESSION['cart']
        ]);
        break;

    case 'OPTIONS':
        // Handle CORS preflight
        http_response_code(200);
        exit();
        break;

    default:
        echo json_encode(['error' => 'Invalid request method']);
        break;
}

// Add product to cart
function addToCart($product) {
    $product_id = $product['id'];
    
    // Check if product already exists in cart
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] += 1;
            $found = true;
            break;
        }
    }
    
    // If not found, add new item
    if (!$found) {
        $_SESSION['cart'][] = [
            'id' => $product['id'],
            'name' => $product['name'],
            'code' => $product['code'],
            'price' => $product['price'],
            'quantity' => 1
        ];
    }
}

// Remove product from cart
function removeFromCart($product_id) {
    $_SESSION['cart'] = array_filter($_SESSION['cart'], function($item) use ($product_id) {
        return $item['id'] != $product_id;
    });
    // Re-index array
    $_SESSION['cart'] = array_values($_SESSION['cart']);
}

// Update quantity
function updateQuantity($product_id, $quantity) {
    $quantity = max(0, intval($quantity));
    
    if ($quantity <= 0) {
        removeFromCart($product_id);
    } else {
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $product_id) {
                $item['quantity'] = $quantity;
                break;
            }
        }
    }
}

// Clear entire cart
function clearCart() {
    $_SESSION['cart'] = [];
}
?>
