<?php

require_once '../config/connection.php';

// Insertar categoría en la base de datos
$con = new connection();
$query = $con->run(
    "INSERT INTO categories (name)
     VALUES ('{$_POST['categoryName']}')"
);

// Redireccionar a la página principal
header('Location: ../index.php');

?>