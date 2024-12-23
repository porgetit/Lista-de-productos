<?php
require_once '../config/connection.php';

// Obtener categorías y subcategorías de la base de datos
$con = new connection();
$query = $con->run(
    "SELECT c.id AS category_id, c.name AS category_name, s.id AS subcategory_id, s.name AS subcategory_name
     FROM categories c
     LEFT JOIN subcategories s ON c.id = s.category_id
     ORDER BY c.name, s.name"
);

// Estructurar datos en un array para convertirlo en JSON
$data = [];
while ($row = $query->fetch_assoc()) {
    $category_id = $row['category_id'];
    $category_name = $row['category_name'];
    $subcategory_id = $row['subcategory_id'];
    $subcategory_name = $row['subcategory_name'];

    if (!isset($data[$category_id])) {
        $data[$category_id] = [
            'name' => $category_name,
            'subcategories' => []
        ];
    }

    if ($subcategory_id) {
        $data[$category_id]['subcategories'][] = [
            'id' => $subcategory_id,
            'name' => $subcategory_name
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
    <title>Agregar Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container my-5">
        <h1 class="text-center">Agregar Producto Nuevo</h1>
        <form action="add_product_process.php" method="POST" class="mt-4" id="productForm">
            <!-- Categoría -->
            <div class="mb-3">
                <label for="productCategory" class="form-label">Categoría</label>
                <select class="form-select" id="productCategory" name="productCategory" required>
                    <option value="" disabled selected>Seleccione una categoría</option>
                </select>
            </div>

            <!-- Subcategoría -->
            <div class="mb-3">
                <label for="productSubcategory" class="form-label">Subcategoría</label>
                <select class="form-select" id="productSubcategory" name="productSubcategory" disabled>
                    <option value="" disabled selected>Seleccione una subcategoría</option>
                </select>
            </div>

            <!-- Nombre del producto -->
            <div class="mb-3">
                <label for="productName" class="form-label">Nombre del Producto</label>
                <input type="text" class="form-control" id="productName" name="productName" placeholder="Ingrese el nombre del producto" required>
            </div>

            <!-- Precio -->
            <div class="mb-3">
                <label for="productPrice" class="form-label">Precio</label>
                <input type="number" class="form-control" id="productPrice" name="productPrice" placeholder="Ingrese el precio del producto" step="1" required>
            </div>

            <!-- Botón de enviar -->
            <div class="d-grid">
                <button type="submit" class="btn btn-success">Agregar Producto</button>
            </div>
        </form>
    </div>

    <script>
        // Datos en JSON desde el servidor
        const categoriesData = <?= $jsonData ?>;

        // Elementos del formulario
        const categorySelect = document.getElementById('productCategory');
        const subcategorySelect = document.getElementById('productSubcategory');

        // Cargar categorías en el selector
        for (const categoryId in categoriesData) {
            const category = categoriesData[categoryId];
            const option = document.createElement('option');
            option.value = categoryId;
            option.textContent = category.name;
            categorySelect.appendChild(option);
        }

        // Evento al cambiar la categoría seleccionada
        categorySelect.addEventListener('change', function () {
            const selectedCategoryId = this.value;
            const subcategories = categoriesData[selectedCategoryId]?.subcategories || [];

            // Resetear opciones de subcategoría
            subcategorySelect.innerHTML = '<option value="" disabled selected>Seleccione una subcategoría</option>';

            // Agregar subcategorías si hay resultados
            if (subcategories.length > 0) {
                subcategorySelect.disabled = false;
                subcategories.forEach(subcat => {
                    const option = document.createElement('option');
                    option.value = subcat.id;
                    option.textContent = subcat.name;
                    subcategorySelect.appendChild(option);
                });
            } else {
                subcategorySelect.disabled = true;
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
