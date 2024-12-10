# Catálogo de Productos

Esta es una aplicación web que facilita la visualización, filtrado y modificación de precios de productos en una base de datos local. El proyecto está diseñado para operar de manera sencilla y eficiente en un entorno local con XAMPP y MariaDB.

## Funcionalidades

- **Visualización de productos:** Muestra una lista completa de productos organizados por categorías y subcategorías.
- **Filtrado dinámico:** Permite buscar productos por nombre, subcategoría o categoría.
- **Edición de precios:** Los usuarios pueden modificar los precios de productos de forma individual directamente desde la interfaz web.

## Requerimientos

### A nivel de base de datos:

- **Servidor de base de datos:** MariaDB
- **Estructura de la base de datos:** Incluye tres tablas principales:
  - `categories` (categorías de productos)
  - `subcategories` (subcategorías vinculadas a categorías)
  - `products` (productos con referencia a subcategorías)

### A nivel de servidor web:

- XAMPP (o cualquier servidor con soporte para PHP y MariaDB)
- PHP 7.4 o superior

## Instalación

### Configuración de la base de datos

1. Instala y configura XAMPP o un servidor equivalente.
2. Crea una base de datos en MariaDB utilizando el siguiente script SQL:

```sql
-- Crear base de datos y estructura
CREATE DATABASE IF NOT EXISTS ProductCatalog;
USE ProductCatalog;

-- Crear tabla para categorías
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);

-- Crear tabla para subcategorías
CREATE TABLE IF NOT EXISTS subcategories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) UNIQUE,
    category_id INT,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- Crear tabla para productos
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    subcategory_id INT DEFAULT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (subcategory_id) REFERENCES subcategories(id)
);

-- Crear vista para facilitar consultas
CREATE VIEW IF NOT EXISTS products_view AS
SELECT 
    products.name AS name,
    categories.name AS category,
    subcategories.name AS subcategory,
    products.price AS price
FROM 
    products
    INNER JOIN subcategories ON products.subcategory_id = subcategories.id
    INNER JOIN categories ON subcategories.category_id = categories.id
ORDER BY products.name;
```

3. Inserta datos iniciales en la base datos con un script similar a este

```sql
-- Insertar datos iniciales
INSERT INTO categories (name) VALUES
    ("Tuberia Sanitaria"),
    ("Accesorios PVC"),
    ("Soldaduras para plomería");

INSERT INTO subcategories (name, category_id) VALUES
    ("Tubo pesado", (SELECT id FROM categories WHERE name = "Tuberia Sanitaria")),
    ("Tubo semipesado", (SELECT id FROM categories WHERE name = "Tuberia Sanitaria")),
    ("Unión PVC", (SELECT id FROM categories WHERE name = "Accesorios PVC")),
    ("Tee PVC", (SELECT id FROM categories WHERE name = "Accesorios PVC")),
    ("Soldadura PVC", (SELECT id FROM categories WHERE name = "Soldaduras para plomería")),
    ("Soldadura CPVC", (SELECT id FROM categories WHERE name = "Soldaduras para plomería"));

INSERT INTO products (name, subcategory_id, price) VALUES
    ("Tubo pesado 1 1/2 x 6 metros", (SELECT id FROM subcategories WHERE name = "Tubo pesado"), 27500),
    ("Tubo pesado 1 1/2 x 1 metro", (SELECT id FROM subcategories WHERE name = "Tubo pesado"), 5600),
    ("Unión 1/2", (SELECT id FROM subcategories WHERE name = "Unión PVC"), 900),
    ("Soldadura 1/128", (SELECT id FROM subcategories WHERE name = "Soldadura PVC"), 6500);
```

### Configuración del servidor web

1. Copia los archivos fuente del proyecto en el directorio `htdocs/<nombre_del_proyecto>` de `XAMPP`
2. Configura el archivo de conexión en config/connection.php para ajustar los datos de acceso a la base de datos.
```php
$server = 'localhost';
$database = 'ProductCatalog';
$user = 'root';
$password = '';
```
3. Abre el navegador y navega a `https://localhost/<nombre_de_proyecto>`

### Uso

1. Filtra productos ingresando texto en la barra de búsqueda.
2. Modifica precios utilizando el ícono de edición al lado de cada producto.
3. Los cambios se reflejan automáticamente en la base de datos.

### Tecnologías utilizadas

* PHP para la lógica de negocio.
* MariaDB para la gestión de datos.
* BootStrap para una interfaz responsiva y estilizada.


## Diccionario de Datos - Base de Datos ProductCatalog

### 1. **Categorías** (`categories`)
| Campo      | Tipo          | Restricciones                 | Descripción                                  |
|------------|---------------|-------------------------------|----------------------------------------------|
| `id`       | INT           | PRIMARY KEY, AUTO_INCREMENT   | Identificador único de la categoría.        |
| `name`     | VARCHAR(100)  | NOT NULL, UNIQUE              | Nombre único de la categoría.               |

---

### 2. **Subcategorías** (`subcategories`)
| Campo          | Tipo          | Restricciones                 | Descripción                                      |
|----------------|---------------|-------------------------------|--------------------------------------------------|
| `id`           | INT           | PRIMARY KEY, AUTO_INCREMENT   | Identificador único de la subcategoría.         |
| `name`         | VARCHAR(100)  | UNIQUE                        | Nombre único de la subcategoría.                |
| `category_id`  | INT           | FOREIGN KEY (`categories.id`) | Identificador de la categoría asociada.         |

---

### 3. **Productos** (`products`)
| Campo             | Tipo          | Restricciones                 | Descripción                                  |
|-------------------|---------------|-------------------------------|----------------------------------------------|
| `id`              | INT           | PRIMARY KEY, AUTO_INCREMENT   | Identificador único del producto.           |
| `name`            | VARCHAR(100)  | NOT NULL                      | Nombre del producto.                        |
| `subcategory_id`  | INT           | FOREIGN KEY (`subcategories.id`), DEFAULT NULL | Identificador de la subcategoría asociada. |
| `price`           | DECIMAL(10,2) | NOT NULL                      | Precio del producto.                        |

---

### 4. **Vista de Productos** (`products_view`)
| Campo          | Tipo          | Fuente                       | Descripción                                      |
|----------------|---------------|------------------------------|--------------------------------------------------|
| `name`         | VARCHAR(100)  | `products.name`              | Nombre del producto.                            |
| `category`     | VARCHAR(100)  | `categories.name`            | Nombre de la categoría asociada al producto.    |
| `subcategory`  | VARCHAR(100)  | `subcategories.name`         | Nombre de la subcategoría asociada al producto. |
| `price`        | DECIMAL(10,2) | `products.price`             | Precio del producto.                            |

---

### Relaciones entre Tablas
1. **`categories` ↔ `subcategories`**: Relación 1:N mediante `subcategories.category_id`.
2. **`subcategories` ↔ `products`**: Relación 1:N mediante `products.subcategory_id`.

---

### Restricciones y Consideraciones
- **Restricción de unicidad**: Los nombres en las tablas `categories` y `subcategories` deben ser únicos.
- **Restricción de integridad referencial**: 
  - Si se elimina una categoría, sus subcategorías asociadas también se eliminarán automáticamente (ON DELETE CASCADE).
  - Si se elimina una subcategoría, los productos asociados quedarán sin subcategoría (`NULL`).

