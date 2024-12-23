<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Categoría</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container my-5">
        <h1 class="text-center">Agregar Categoría Nueva</h1>
        <form action="add_category_process.php" method="POST" class="mt-4" id="categoryForm">
            <!-- Nombre de la categoría -->
            <div class="mb-3">
                <label for="categoryName" class="form-label">Nombre de la Categoría</label>
                <input type="text" class="form-control" id="categoryName" name="categoryName" placeholder="Ingrese el nombre de la categoría" required>
            </div>

            <!-- Botón de enviar -->
            <div class="d-grid">
                <button type="submit" class="btn btn-success">Agregar Categoría</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
