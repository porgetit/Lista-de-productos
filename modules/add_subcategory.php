<?php
require_once '../config/connection.php';

// Obtener categorías y subcategorías de la base de datos
$con = new connection();
$query = $con->run("select id, name from categories order by name asc");

// Estructurar datos en un array para convertirlo en JSON
$data = [];
while ($row = $query->fetch_assoc()) {
    $category_id = $row['id'];
    $category_name = $row['name'];

    if (!isset($data[$category_id])) {
        $data[$category_id] = [
            'name' => $category_name,
        ];
    }
}

// Convertir datos a JSON
$jsonData = json_encode($data);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Subcategoría</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container my-5">
        <h1 class="text-center">Agregar Subcategoría Nueva</h1>
        <form action="add_subcategory_process.php" method="POST" class="mt-4" id="subcategoryForm">
            <!-- Categoría -->
            <div class="mb-3">
                <label for="category" class="form-label">Categoría</label>
                <select class="form-select" id="category" name="category" required>
                    <option value="" disabled selected>Seleccione una categoría</option>
                </select>
            </div>

            <!-- Nombre de la subcategoría -->
            <div class="mb-3">
                <label for="subcategoryName" class="form-label">Nombre de la Subcategoría</label>
                <input type="text" class="form-control" id="subcategoryName" name="subcategoryName" placeholder="Ingrese el nombre de la subcategoría" required>
            </div>

            <!-- Botón de enviar -->
            <div class="d-grid">
                <button type="submit" class="btn btn-success">Agregar Subcategoría</button>
            </div>
        </form>
    </div>

    <script>
        // Datos en JSON desde el servidor
        const categoriesData = <?= $jsonData ?>;

        // Elementos del formulario
        const categorySelect = document.getElementById('category');

        // Cargar categorías en el selector
        for (const categoryId in categoriesData) {
            const category = categoriesData[categoryId];
            const option = document.createElement('option');
            option.value = categoryId;
            option.textContent = category.name;
            categorySelect.appendChild(option);
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
