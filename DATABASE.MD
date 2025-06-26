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
    item_ingredients        --> starbucksitem and ingredient relation table
    user                    --> User of website "Customer"
    admin                   --> Manager
    auth                    --> Logins (user, admin)
    address                 --> Location (user, supplier) for delivery & supply purposes
    userorder               --> Individual order itself contains price & quantity
    order_item              --> contains the userorders to combine all and can get the total price etc.
    receipt                 --> Contains receipt of the order (payment, change etc.)

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
        attribute_template_id INT NOT NULL,
        attribute_value VARCHAR(30) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (item_id) REFERENCES starbucksitem(id) ON DELETE CASCADE,
        FOREIGN KEY (attribute_template_id) REFERENCES attribute_template(id) ON DELETE CASCADE

Ingredient --> 
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL UNIQUE,
        supplier_id INT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE CASCADE

Item Ingredient -->
        id               INT AUTO_INCREMENT PRIMARY KEY,
        item_id          INT NOT NULL,
        ingredient_id    INT NOT NULL,
        quantity_value   DECIMAL(10,4) NULL,
        quantity_unit    VARCHAR(50)   NULL,
        created_at       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at       TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
                          ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (item_id)       REFERENCES starbucksitem(id)   ON DELETE CASCADE,
        FOREIGN KEY (ingredient_id) REFERENCES ingredient(id)      ON DELETE CASCADE,
        UNIQUE (item_id, ingredient_id)

Supplier -->
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        info TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP

Admin -->
        id INT AUTO_INCREMENT PRIMARY KEY,
        first_name VARCHAR(50) NOT NULL,
        middle_name VARCHAR(50),
        last_name VARCHAR(50) NOT NULL,
        role VARCHAR(50) DEFAULT 'admin',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP

User -->
        id INT AUTO_INCREMENT PRIMARY KEY,
        first_name VARCHAR(50) NOT NULL,
        middle_name VARCHAR(50),
        last_name VARCHAR(50) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP

Auth -->
        id INT AUTO_INCREMENT PRIMARY KEY,
        account_type ENUM('user', 'admin') NOT NULL,
        account_id INT NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        CONSTRAINT uniq_auth_account UNIQUE (account_type, account_id),
        INDEX idx_auth_type_id (account_type, account_id)

Contact -->
        id INT AUTO_INCREMENT PRIMARY KEY,
        contactable_type ENUM('supplier', 'user', 'admin') NOT NULL,
        contactable_id INT NOT NULL,
        contact_type ENUM('email', 'phone') NOT NULL,
        value VARCHAR(100) NOT NULL,
        UNIQUE (contactable_type, contactable_id, contact_type, value),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP

Address -->
        id INT AUTO_INCREMENT PRIMARY KEY,
        addressable_type ENUM('user', 'supplier', 'admin') NOT NULL,
        addressable_id INT NOT NULL,
        street VARCHAR(255),
        city VARCHAR(100),
        province VARCHAR(100),
        postal_code VARCHAR(20),
        country VARCHAR(100),
        CONSTRAINT fk_address_unique UNIQUE (addressable_type, addressable_id)

Userorder --> 
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        total_amount DECIMAL(10,2) DEFAULT 0,
        status VARCHAR(50) DEFAULT 'pending',
        placed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE
        
Orderitem -->
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_id INT NOT NULL,
        item_id INT NOT NULL,
        quantity INT NOT NULL,
        unit_price DECIMAL(10,2) NOT NULL,
        total_price DECIMAL(10,2) GENERATED ALWAYS AS (quantity * unit_price) STORED,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (order_id) REFERENCES `userorder`(id) ON DELETE CASCADE,
        FOREIGN KEY (item_id) REFERENCES starbucksitem(id) ON DELETE CASCADE

Receipts -->
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_id INT NOT NULL,
        discount_type ENUM('none', 'senior', 'store_card', 'custom') DEFAULT 'none',
        discount_value DECIMAL(5,2) DEFAULT 0.00, -- percentage like 10.00 or 12.00
        discount_amount DECIMAL(10,2) DEFAULT 0.00, -- actual amount deducted
        final_amount DECIMAL(10,2) NOT NULL, -- total after discount
        payment_amount DECIMAL(10,2) NOT NULL, -- amount customer paid
        change_amount DECIMAL(10,2) GENERATED ALWAYS AS (payment_amount - final_amount) STORED,
        issued_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (order_id) REFERENCES userorder(id) ON DELETE CASCADE


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


