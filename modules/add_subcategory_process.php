<?php

// Incluir archivo de conexión a base de datos
require_once '../config/connection.php';

// Insertar subcategoría en la base de datos
$con = new connection();
$query = $con->run(
    "INSERT INTO subcategories (name, category_id)
     VALUES ('{$_POST['subcategoryName']}', {$_POST['category']})"
);

// Redireccionar a la página principal
header('Location: ../index.php');

?>