<?php
class Item {
    
    public static function getAll() {
        global $conn; // This $conn is from db.php and tis global , by using this we can freely access the database 
        $sql = "SELECT * FROM starbucksitems";  // selecting all items in starbucksitem using sql commands SELECT * FROM
        $result = $conn->query($sql); // Process the SELECT * FROM by calling the database ($conn) commmand query

        if (!$result) {
            return [];  // if result is faild return error
        }

        return $result->fetch_all(MYSQLI_ASSOC); // else, convert the SELECT * FROM into a php array like this ['id' => 1, 'name' => 'Espresso', 'price' => 120],
    }

    public static function insert($name, $price, $category_id, $subcategory_id, $description = null) {
    global $conn;

    $stmt = $conn->prepare("
        INSERT INTO starbucksitem (name, price, category_id, subcategory_id, description)
        VALUES (?, ?, ?, ?, ?)
    ");
    
    $stmt->bind_param("sdiis", $name, $price, $category_id, $subcategory_id, $description);
    
    return $stmt->execute();
}

}

?>

// getAll --> Gets all the function
// insert --> Insert starbucksitem
