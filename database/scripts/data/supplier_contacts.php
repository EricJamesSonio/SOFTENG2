<?php
require_once(__DIR__ . '/../../db.php');
require_once(__DIR__ . '/../function.php');


$contacts = [];
$suppliers = [
    'BeanSource Inc.'     => ['phone' => '09171234567', 'email' => 'beans@beansource.com'],
    'DairyGold Supplies'  => ['phone' => '09281234567', 'email' => 'contact@dairygold.ph'],
    'Sunrise Bakery Co.'  => ['phone' => '09391234567', 'email' => 'sales@sunrisebakery.com.ph'],
    'TeaLeaf Traders'     => ['phone' => '09081234567', 'email' => 'hello@tealeaftraders.com']
];

foreach ($suppliers as $name => $methods) {
    $supplierId = getIdByName($con, 'suppliers', $name);
    if (!$supplierId) continue;

    foreach ($methods as $type => $value) {
        $contacts[] = [$supplierId, $type, $value];
    }
}

insertData($con, 'supplier_contacts', ['supplier_id', 'contact_type', 'value'], $contacts);
?>
