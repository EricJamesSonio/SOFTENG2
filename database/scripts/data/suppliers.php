<?php
require_once(__DIR__ . '/../../db.php');
require_once(__DIR__ . '/../function.php');



insertData($con, 'suppliers',
    ['name', 'contact_no', 'email', 'info'],
    [
        ['BeanSource Inc.', '09171234567', 'beans@beansource.com', 'Espresso bean supplier'],
        ['DairyGold Supplies', '09281234567', 'contact@dairygold.ph', 'Milk and cheese supplier'],
        ['Sunrise Bakery Co.', '09391234567', 'sales@sunrisebakery.com.ph', 'Bun and bread products'],
        ['TeaLeaf Traders', '09081234567', 'hello@tealeaftraders.com', 'Tea bag and flavor syrup']
    ]
);
?>
