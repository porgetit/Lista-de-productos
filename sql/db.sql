-- Create database
CREATE DATABASE IF NOT EXISTS ProductCatalog;
USE ProductCatalog;

-- Create table for categories
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);

-- Create table for subcategories
CREATE TABLE IF NOT EXISTS subcategories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) UNIQUE,
    category_id INT,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- Create table for products
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    subcategory_id INT DEFAULT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (subcategory_id) REFERENCES subcategories(id)
);

-- Insert categories
INSERT INTO categories (name) VALUES
    ("Tuberia Sanitaria"),
    ("Accesorios PVC"),
    ("Soldaduras para plomería");

-- Insert subcategories
INSERT INTO subcategories (name, category_id) VALUES
    ("Tubo pesado", (select id from categories where name = "Tuberia Sanitaria")),
    ("Tubo semipesado", (select id from categories where name = "Tuberia Sanitaria")),
    ("Unión PVC", (select id from categories where name = "Accesorios PVC")),
    ("Tee PVC", (select id from categories where name = "Accesorios PVC")),
    ("Soldadura PVC", (select id from categories where name = "Soldaduras para plomería")),
    ("Soldadura CPVC", (select id from categories where name = "Soldaduras para plomería"));

-- Insert products
INSERT INTO products (name, subcategory_id, price) VALUES
    ("Tubo pesado 1 1/2 x 6 metros", (select id from subcategories where name = "Tubo pesado"), 27500),
    ("Tubo pesado 1 1/2 x 1 metro", (select id from subcategories where name = "Tubo pesado"), 5600),
    ("Tubo pesado 2 x 6 metros", (select id from subcategories where name = "Tubo pesado"), 38600),
    ("Tubo pesado 2 x 1 metro", (select id from subcategories where name = "Tubo pesado"), 7500),
    ("Tubo semipesado 1 1/2 x 6 metros", (select id from subcategories where name = "Tubo semipesado"), 21800),
    ("Tubo semipesado 1 1/2 x 1 metro", (select id from subcategories where name = "Tubo semipesado"), 6000),
    ("Tubo semipesado 2 x 6 metros", (select id from subcategories where name = "Tubo semipesado"), 27500),
    ("Tubo semipesado 2 x 1 metro", (select id from subcategories where name = "Tubo semipesado"), 6800),
    ("Unión 1/2", (select id from subcategories where name = "Unión PVC"), 900),
    ("Unión 3/4", (select id from subcategories where name = "Unión PVC"), 1200),
    ("Tee 1/2", (select id from subcategories where name = "Tee PVC"), 1200),
    ("Tee 3/4", (select id from subcategories where name = "Tee PVC"), 1800),
    ("Soldadura 1/256", (select id from subcategories where name = "Soldadura PVC"), 3000),
    ("Soldadura 1/128", (select id from subcategories where name = "Soldadura PVC"), 6500),
    ("Soldadura 1/128", (select id from subcategories where name = "Soldadura CPVC"), 8200),
    ("Soldadura 1/64", (select id from subcategories where name = "Soldadura CPVC"), 13200);



create view if not exists products_view as
select 
	products.name as name, 
    categories.name as category, 
    subcategories.name as subcategory,
    products.price as price
from 
	products inner join subcategories on products.subcategory_id = subcategories.id 
    inner join categories on subcategories.category_id = categories.id 
order by products.name;