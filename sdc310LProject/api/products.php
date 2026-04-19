<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

// Sample products data (can be replaced with database queries)
$products = [
    [
        'id' => 1,
        'name' => 'Wireless Headphones',
        'description' => 'High-quality wireless headphones with noise cancellation and 30-hour battery life',
        'code' => 'WH-001',
        'price' => 79.99
    ],
    [
        'id' => 2,
        'name' => 'USB-C Cable',
        'description' => 'Durable 6ft USB-C charging and data transfer cable, compatible with all USB-C devices',
        'code' => 'USB-002',
        'price' => 14.99
    ],
    [
        'id' => 3,
        'name' => 'Portable Power Bank',
        'description' => '20000mAh portable power bank with dual USB ports and fast charging capability',
        'code' => 'PB-003',
        'price' => 34.99
    ],
    [
        'id' => 4,
        'name' => 'Phone Screen Protector',
        'description' => 'Tempered glass screen protector with 9H hardness and anti-fingerprint coating',
        'code' => 'SP-004',
        'price' => 9.99
    ],
    [
        'id' => 5,
        'name' => 'Bluetooth Speaker',
        'description' => 'Compact waterproof Bluetooth speaker with 12-hour battery and 360-degree sound',
        'code' => 'BS-005',
        'price' => 49.99
    ]
];

echo json_encode($products);
?>
