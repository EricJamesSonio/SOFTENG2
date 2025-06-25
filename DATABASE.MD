******************************* DATABASE SUMMARY **********************************

[model]         --> contains Table for DATABASE
    attributes_template     --> Coffeine level etc.
    category                --> Drinks, Sandwich etc.
    ingredients             --> Used in starbucksitems
    itemattributes          --> Contains Attribute templates and starcbucksitem linked
    starbucksitem           --> Base Item
    subcategory             --> Drinks = Expresso
    supplier_contacts       --> Email, Phone
    supplier                --> Base Details of Ingredient Suppliers
[new] item_ingredients      --> starbucksitem and ingredient relation table

StarbucksItem -->
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        price DECIMAL(10,2) NOT NULL,
        quantity INT DEFAULT 0,
        category_id INT NOT NULL,
        subcategory_id INT NOT NULL,
        description TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (category_id) REFERENCES category(id),
        FOREIGN KEY (subcategory_id) REFERENCES subcategory(id)

Category -->
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(50) NOT NULL
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP

Subcategory --> 
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(50) NOT NULL,
        category_id INT NOT NULL,
        FOREIGN KEY (category_id) REFERENCES category(id) ON DELETE CASCADE

Attribute Templates -->
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

Item Attributes --> 
        id INT AUTO_INCREMENT PRIMARY KEY,
        item_id INT NOT NULL,
        attribute_name VARCHAR(50) NOT NULL,
        attribute_value VARCHAR(100) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (item_id) REFERENCES starbucksitem(id) ON DELETE CASCADE

Ingredients --> 
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL UNIQUE,
        supplier_id INT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE CASCADE

Supplier -->
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        info TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP


Contact -->
        id INT AUTO_INCREMENT PRIMARY KEY,
        contactable_type ENUM('supplier', 'user', 'admin') NOT NULL,
        contactable_id INT NOT NULL,
        contact_type ENUM('email', 'phone') NOT NULL,
        value VARCHAR(100) NOT NULL,
        UNIQUE (contactable_type, contactable_id, contact_type, value),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP

Item Ingredients -->
        id INT AUTO_INCREMENT PRIMARY KEY,
        item_id INT NOT NULL,
        ingredient_id INT NOT NULL,
        quantity VARCHAR(50), -- e.g., '2 slices', '1 scoop'
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (item_id) REFERENCES starbucksitem(id) ON DELETE CASCADE,
        FOREIGN KEY (ingredient_id) REFERENCES ingredients(id) ON DELETE CASCADE,
        UNIQUE (item_id, ingredient_id) -- prevent duplicate ingredient entries for the same item

Admins -->
        id INT AUTO_INCREMENT PRIMARY KEY,
        first_name VARCHAR(50) NOT NULL,
        middle_name VARCHAR(50),
        last_name VARCHAR(50) NOT NULL,
        role VARCHAR(50) DEFAULT 'admin',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP

Users -->
        id INT AUTO_INCREMENT PRIMARY KEY,
        first_name VARCHAR(50) NOT NULL,
        middle_name VARCHAR(50),
        last_name VARCHAR(50) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP

Auths -->
        id INT AUTO_INCREMENT PRIMARY KEY,
        account_type ENUM('user', 'admin') NOT NULL,
        account_id INT NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        CONSTRAINT uniq_auth_account UNIQUE (account_type, account_id),
        INDEX idx_auth_type_id (account_type, account_id)



[scripts]

db.php          --> Database creation 

set.php         --> Executing the creation of TABLES and pre defined fields in DATABASE

[functions]

createTable     --> Create The Tables in the Database only Onced and Updates for deleted rows or added rows   
insertData      --> Insert pre defined fields only onced
upsertData      --> Can be use for manually updating Tables
getIDbyName     --> Get id from different tables by name (Used for creating tables that is required foreign keys)


**************************************** NORMALIZATION DESIGN DATABASE *************************************

[User] -----------------|----------------------|
                        |                      |----[Auth] --> Logins
[Admin]-----------------|----------------------|
                        |-------- [Contacts] --> Phones, Emails ---- [Address]
[Supplier]--------------|

--------------------------------------------------------------------------------
[Contacts] --- Phone, Emails
        [User],[Admin],[Supplier]

[Auth] --- Login Password username 
        [User], [Admin]

[Address] -- Location
        [User], [Admin]


