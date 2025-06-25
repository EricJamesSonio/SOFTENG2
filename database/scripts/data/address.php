<?php
require_once(__DIR__ . '/../function.php');

$addresses = [];

// Supplier addresses
$supplierAddresses = [
    'BeanSource Inc.' => [
        'street' => '123 Coffee Ave',
        'city' => 'Manila',
        'province' => 'Metro Manila',
        'postal_code' => '1000',
        'country' => 'Philippines'
    ],
    'DairyGold Supplies' => [
        'street' => '456 Dairy Rd',
        'city' => 'Santa Rosa',
        'province' => 'Laguna',
        'postal_code' => '4026',
        'country' => 'Philippines'
    ],
    'Sunrise Bakery Co.' => [
        'street' => '789 Bakery St',
        'city' => 'Quezon City',
        'province' => 'Metro Manila',
        'postal_code' => '1100',
        'country' => 'Philippines'
    ],
    'TeaLeaf Traders' => [
        'street' => '321 Tea Hill',
        'city' => 'Baguio',
        'province' => 'Benguet',
        'postal_code' => '2600',
        'country' => 'Philippines'
    ]
];

foreach ($supplierAddresses as $name => $info) {
    $supplierId = getIdByName($con, 'supplier', $name);
    if (!$supplierId) continue;

    $addresses[] = ['supplier', $supplierId, $info['street'], $info['city'], $info['province'], $info['postal_code'], $info['country']];
}

// User address
$userId = getIdByFullName($con, 'user', 'Juan', 'Cruz');
if ($userId) {
    $addresses[] = ['user', $userId, '123 Mango St', 'Makati', 'Metro Manila', '1226', 'Philippines'];
}

// Admin address
$adminId = getIdByFullName($con, 'admin', 'Maria', 'Santos');
if ($adminId) {
    $addresses[] = ['admin', $adminId, '456 Admin Ave', 'Pasig', 'Metro Manila', '1600', 'Philippines'];
}

// Insert all
insertData($con, 'address',
    ['addressable_type', 'addressable_id', 'street', 'city', 'province', 'postal_code', 'country'],
    $addresses,
    ['addressable_type', 'addressable_id'] // Unique constraint for duplicate checking
);
