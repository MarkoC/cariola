# Cariola - simple helper for PHP developers

----


## Installation and usage


########## CONFIGURE DB ###########################

Open: /cariola/src/Cariola/Helper/Db.php
 
 private static $config = array(
        'default' => array(
            'user' => 'root',
            'pass' => '',
            'host' => 'localhost',
            'dbname' => '',
        ) ,
        'test' => array(
            'user' => 'root',
            'pass' => '',
            'host' => 'localhost',
            'dbname' => '',
        ),
    );


########## USE #########################

include Db.php

<?php
require '/cariola/src/Cariola/Helper/Db.php';
?>

Use: Db::query({sql.query}, {$params = array()})

<?php
$result = Db::query('SELECT * FROM {table.name} WHERE id = :id', array(':id' => 1))->fetchAll();
var_dump($result);
?>

Changing the Database:
<?php
Db::dbSetActive('test');
?>
