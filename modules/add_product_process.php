<?php

require_once '../config/connection.php';

// Insertar producto en la base de datos
$con = new connection();
$query = $con->run(
    "INSERT INTO products (name, subcategory_id, price)
     VALUES ('{$_POST['productName']}', {$_POST['productSubcategory']}, {$_POST['productPrice']})"
);

// Redireccionar a la página principal
header('Location: ../index.php');

?>