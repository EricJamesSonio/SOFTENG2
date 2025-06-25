<?php
require_once(__DIR__ . '/db.php');
require_once(__DIR__ . '/scripts/function.php');

// Include table creation files in proper order
require_once(__DIR__ . '/model/category.php');
require_once(__DIR__ . '/model/subcategory.php');
require_once(__DIR__ . '/model/starbucksitem.php');
require_once(__DIR__ . '/model/attributes_templates.php');
require_once(__DIR__ . '/model/itemattributes.php');
require_once(__DIR__ . '/model/supplier.php');       
require_once(__DIR__ . '/model/ingredients.php');    
require_once(__DIR__ . '/model/item_ingredients.php'); 
require_once(__DIR__ . '/model/contacts.php');
require_once(__DIR__ . '/model/users.php');
require_once(__DIR__ . '/model/admins.php');
require_once(__DIR__ . '/model/auth.php');
require_once(__DIR__ . '/model/address.php');
require_once(__DIR__ . '/model/user_order.php');
require_once(__DIR__ . '/model/orderitems.php');
require_once(__DIR__ . '/model/receipts.php');


// Data population
require_once(__DIR__ . '/scripts/data/category_data.php');
require_once(__DIR__ . '/scripts/data/subcategory_data.php');
require_once(__DIR__ . '/scripts/data/attributes_templates_data.php');
require_once(__DIR__ . '/scripts/data/starbucksitem.php');
require_once(__DIR__ . '/scripts/data/item_attributes_data.php');
require_once(__DIR__ . '/scripts/data/suppliers.php');    
require_once(__DIR__ . '/scripts/data/ingredients.php'); 
require_once(__DIR__ . '/scripts/data/item_ingredients.php');
require_once(__DIR__ . '/scripts/data/users.php');
require_once(__DIR__ . '/scripts/data/admins.php');
require_once(__DIR__ . '/scripts/data/contacts.php');
require_once(__DIR__ . '/scripts/data/auth.php');
require_once(__DIR__ . '/scripts/data/address.php');
require_once(__DIR__ . '/scripts/data/sample_order.php');
require_once(__DIR__ . '/scripts/data/receipts.php');


echo "✅ All tables created successfully.";
?>
