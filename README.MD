Soft eng Project --> E commerce "Starbucks"

[Database] --> contains creation of database
--> function logic for creating tables
--> inserting pre define fields only once , if exists skip
--> function for inserting starbucksitem (display in frontend)

[Backend] --> Backend logic

    [api] --> contains multiple routes assigned to different data (items etc.)
        [controller]            --> contains the controller using the files in [model] folder
            ItemController.php  --> uses the fucntions in Item.php from [model]

    index.php --> gets all the request from the frontend
              --> checks the URL type ("/items", "/suppliers" etc.) then call [routes] files for response

    [model] --> contains database logic (using the global "$conn" to access database indirectly)
        [Item.php]          --> contains logic for starbucksitem only
        [Ingredient.php]    --> contains logic for ingredients only
        [Supplier.php]      --> contains logic for suppliers only

    [routes] --> Response for index.php
        ! All this file is using the itemcontroller from [api] to execute functions
        [items.php]         --> request = "/items"
        [ingredients.php]   --> request = "/ingredients"
        [orders.php]        --> request = "/order"
        [suppliers.php]     --> request = "/supplier"

******************************* BACKEND SUMMARY **********************************

[Backend Flow]

[API FLOW]
index.php --> checks URL type . ex. ("/items")
--> require = [routes/items.php] -- calls the items.php from [routes] folder
--> items.php uses the [controllers/itemcontroller.php] for response
--> [controllers/itemcontroller.php] uses [model/Item.php] logic functions business and database

[Folders]

-------------- Models and Controller connection --------------
[models]        --> Interact with DATABASE "Functions" ex. SELECT * FROM etc.
[controllers]   --> Checking Request Method "GET/POST/PUT/PATCH" and Validation before calls [models] functions

-------------- routes and index.php connection --------------
index.php       --> Main API checks the request ex. ("/items", "/suppliers")
                --> each request has equivalent routes
                --> ex. "/items" = [routes] -- items.php

[routes]        -->  use controller to respond from the request 
                --> index.php calls the files inside to respond


******************************* DATABASE SUMMARY **********************************

[model]         --> contains Table for DATABASE
    attributes_template     --> Coffeine level etc.
    category                --> Drinks, Sandwich etc.
    ingredients             --> "To follow"
    itemattributes          --> Contains Attribute templates and starcbucksitem linked
    starbucksitem           --> Base Item
    subcategory             --> Drinks = Expresso
    supplier_contacts       --> Email, Phone
    supplier                --> Base Details of Ingredient Suppliers

[scripts]

db.php          --> Database creation 

set.php         --> Executing the creation of TABLES and pre defined fields in DATABASE
