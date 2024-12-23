<?php
require_once './config/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['productName'], $_POST['newPrice'])) {
    $productName = $_POST['productName'];
    $newPrice = $_POST['newPrice'];

    // Update the price in the database
    $con = new connection();
    $stmt = $con->run("UPDATE products_view SET price = '{$newPrice}' WHERE name = '{$productName}'");

    // Reload the page to reflect changes
    header("Location: {$_SERVER['PHP_SELF']}");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">
    <div class="container my-4">
        <h1 class="text-center mb-4">Listado de Productos</h1>
        <div class="mb-3 d-flex justify-content-between align-items-center">
            <input type="text" id="searchBar" class="form-control me-3" placeholder="Buscar por producto, subcategoría o categoría" oninput="filterProducts()">
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    Agregar
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li><a class="dropdown-item" href="./modules/add_product.php">Nuevo Producto</a></li>
                    <li><a class="dropdown-item" href="./modules/add_subcategory.php">Nueva Subcategoría</a></li>
                    <li><a class="dropdown-item" href="./modules/add_category.php">Nueva Categoría</a></li>
                </ul>
            </div>
        </div>
        <div id="productList" class="accordion">
            <!-- Product items will be dynamically populated here -->
        </div>
    </div>

    <script>
        <?php
        $con = new connection();
        $result = $con->run("SELECT name, category, subcategory, price FROM products_view;");
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        ?>

        // Dynamically generated products data from PHP
        const products = <?php echo json_encode($products, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;

        // Function to group products by category and subcategory
        function groupProducts(products) {
            const grouped = {};
            products.forEach(product => {
                if (!grouped[product.category]) {
                    grouped[product.category] = {};
                }
                if (!grouped[product.category][product.subcategory || "Uncategorized"]) {
                    grouped[product.category][product.subcategory || "Uncategorized"] = [];
                }
                grouped[product.category][product.subcategory || "Uncategorized"].push(product);
            });
            return grouped;
        }

        // Function to handle editing price
        function editPrice(productName, currentPrice) {
            const productList = document.getElementById('productList');
            const itemToEdit = document.querySelector(`[data-name="${productName}"]`);

            // Temporarily replace item content with an inline form
            itemToEdit.innerHTML = `
                <form method="POST" action="">
                    <input type="hidden" name="productName" value="${productName}">
                    <div class="d-flex justify-content-between">
                        <strong>${productName}</strong>
                        <input type="number" name="newPrice" value="${currentPrice}" step="1" class="form-control w-25">
                        <button type="submit" class="btn btn-success btn-sm ms-2">Save</button>
                    </div>
                </form>
            `;
        }

        // Function to display products grouped by category and subcategory
        function displayProducts(filteredProducts) {
            const grouped = groupProducts(filteredProducts);
            const productList = document.getElementById('productList');
            productList.innerHTML = '';

            Object.keys(grouped).sort().forEach(category => {
                const categorySection = document.createElement('div');
                categorySection.className = 'mb-3';

                const categoryHeader = document.createElement('h3');
                categoryHeader.className = 'text-primary';
                categoryHeader.textContent = category;

                categorySection.appendChild(categoryHeader);

                Object.keys(grouped[category]).sort().forEach(subcategory => {
                    const subcategoryHeader = document.createElement('h5');
                    subcategoryHeader.className = 'text-secondary mt-2';
                    subcategoryHeader.textContent = subcategory;

                    const productListGroup = document.createElement('ul');
                    productListGroup.className = 'list-group';

                    grouped[category][subcategory].sort((a, b) => a.name.localeCompare(b.name)).forEach(product => {
                        const listItem = document.createElement('li');
                        listItem.className = 'list-group-item d-flex justify-content-between align-items-center';
                        listItem.dataset.name = product.name;
                        listItem.innerHTML = `
                            <span><strong>${product.name}</strong></span>
                            <div class="d-flex align-items-center">
                                <span class="me-2">$${parseFloat(product.price).toFixed(2)}</span>
                                <button class="btn p-0 border-0 bg-transparent" onclick="editPrice('${product.name}', ${product.price})">
                                    <i class="bi bi-pencil"></i>
                                </button>
                            </div>
                        `;
                        productListGroup.appendChild(listItem);
                    });

                    categorySection.appendChild(subcategoryHeader);
                    categorySection.appendChild(productListGroup);
                });

                productList.appendChild(categorySection);
            });
        }

        // Filter products by name, category, or subcategory
        function filterProducts() {
            const query = document.getElementById('searchBar').value.toLowerCase();
            const filtered = products.filter(product => 
                product.name.toLowerCase().includes(query) ||
                (product.subcategory && product.subcategory.toLowerCase().includes(query)) ||
                product.category.toLowerCase().includes(query)
            );
            displayProducts(filtered);
        }

        // Initial display
        displayProducts(products);
    </script>
</body>
</html>
