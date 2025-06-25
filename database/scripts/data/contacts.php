<?php
require_once(__DIR__ . '/../function.php');

$contacts = [];

// Supplier contacts
$suppliers = [
    'BeanSource Inc.'     => ['phone' => '09171234567', 'email' => 'beans@beansource.com'],
    'DairyGold Supplies'  => ['phone' => '09281234567', 'email' => 'contact@dairygold.ph'],
    'Sunrise Bakery Co.'  => ['phone' => '09391234567', 'email' => 'sales@sunrisebakery.com.ph'],
    'TeaLeaf Traders'     => ['phone' => '09081234567', 'email' => 'hello@tealeaftraders.com']
];

foreach ($suppliers as $name => $methods) {
    $supplierId = getIdByName($con, 'supplier', $name);
    if (!$supplierId) continue;

    foreach ($methods as $type => $value) {
        $contacts[] = ['supplier', $supplierId, $type, $value];
    }
}
// Add user contact
$userId = getIdByFullName($con, 'user', 'Juan', 'Cruz');
if ($userId) {
    $contacts[] = ['user', $userId, 'phone', '09171234567'];
    $contacts[] = ['user', $userId, 'email', 'user1@example.com'];
}

// Add admin contact
$adminId = getIdByFullName($con, 'admin', 'Maria', 'Santos');
if ($adminId) {
    $contacts[] = ['admin', $adminId, 'phone', '09179876543'];
    $contacts[] = ['admin', $adminId, 'email', 'admin1@example.com'];
}

// Insert all
insertData($con, 'contact',
    ['contactable_type', 'contactable_id', 'contact_type', 'value'],
    $contacts
);
