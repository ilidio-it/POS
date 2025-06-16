<?php
session_start();
require_once 'config/database.php';
require_once 'includes/auth.php';

// Check if user is logged in and has permission
if (!isLoggedIn() || !hasPermission('caixa')) {
    header('Location: login.php');
    exit();
}

$currentUser = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales - Cantina Smart Sales</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/components.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main class="container">
        <div class="dashboard-header">
            <h1>Sales</h1>
            <p>Process new sales and manage transactions</p>
        </div>

        <div class="sales-layout">
            <!-- Product Selection Area -->
            <div class="products-section">
                <!-- Search Bar -->
                <div class="search-container">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" id="searchProducts" placeholder="Search products..." />
                    </div>
                </div>

                <!-- Product Categories -->
                <div class="tabs-container">
                    <div class="tabs-list">
                        <button class="tab-trigger active" data-category="all">All</button>
                        <button class="tab-trigger" data-category="Bebidas">Bebidas</button>
                        <button class="tab-trigger" data-category="Hambúrgueres">Hambúrgueres</button>
                        <button class="tab-trigger" data-category="Sandes">Sandes</button>
                        <button class="tab-trigger" data-category="Tostas">Tostas</button>
                        <button class="tab-trigger" data-category="Bolachas">Bolachas</button>
                        <button class="tab-trigger" data-category="Snacks e Doces">Snacks e Doces</button>
                        <button class="tab-trigger" data-category="Cachorros Quentes">Cachorros Quentes</button>
                        <button class="tab-trigger" data-category="Salgadinhos">Salgadinhos</button>
                        <button class="tab-trigger" data-category="Pregos">Pregos</button>
                    </div>
                </div>

                <!-- Products Grid -->
                <div id="productsGrid" class="sales-products-grid">
                    <!-- Products will be loaded here via AJAX -->
                </div>
            </div>
        </div>
    </main>

    <!-- Shopping Cart Sidebar -->
    <div id="cartSidebar" class="cart-sidebar">
        <div class="cart-header">
            <h3>Shopping Cart</h3>
            <button id="closeCart" class="close-btn">&times;</button>
        </div>
        <div id="cartItems" class="cart-items">
            <!-- Cart items will be loaded here -->
        </div>
        <div class="cart-footer">
            <div class="cart-total">
                <strong>Total: <span id="cartTotal">0.00 MT</span></strong>
            </div>
            <div class="cart-actions">
                <button id="clearCart" class="btn btn-outline btn-full">Clear Cart</button>
                <button id="completeSale" class="btn btn-primary btn-full">Complete Sale</button>
            </div>
        </div>
    </div>

    <!-- Floating Cart Button -->
    <button id="cartToggle" class="cart-toggle">
        <i class="fas fa-shopping-cart"></i>
        <span id="cartCount" class="cart-count">0</span>
    </button>

    <script src="assets/js/main.js"></script>
    <script src="assets/js/cart.js"></script>
    <script src="assets/js/products.js"></script>
    
    <style>
        .sales-layout {
            display: flex;
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        .products-section {
            flex: 1;
        }
        
        .sales-products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1rem;
        }
        
        .sales-product-card {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            transition: var(--transition);
            border: 2px solid transparent;
        }
        
        .sales-product-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
            border-color: rgba(16, 185, 129, 0.2);
        }
        
        .sales-product-content {
            padding: 1rem;
        }
        
        .sales-product-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0.5rem;
        }
        
        .sales-product-name {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        
        .sales-product-price {
            font-size: 0.875rem;
            color: var(--primary-color);
            font-weight: 600;
        }
        
        .sales-product-stock {
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .cart-actions {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            margin-top: 1rem;
        }
        
        @media (max-width: 768px) {
            .sales-layout {
                flex-direction: column-reverse;
            }
            
            .sales-products-grid {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            }
        }
    </style>
    
    <script>
        // Override the renderProducts function for sales page
        function renderProducts(products) {
            const productsGrid = document.getElementById('productsGrid');
            
            if (!productsGrid) return;
            
            if (products.length === 0) {
                productsGrid.innerHTML = '<div class="text-center p-6 text-secondary">No products found</div>';
                return;
            }
            
            const productsHTML = products.map(product => `
                <div class="sales-product-card">
                    <div class="sales-product-content">
                        <div class="sales-product-header">
                            <div>
                                <h3 class="sales-product-name">${product.name}</h3>
                                <p class="sales-product-price">${product.price} MT</p>
                            </div>
                            <span class="sales-product-stock ${getStockStatusClass(product.stock)}">
                                ${product.stock > 0 ? `Stock: ${product.stock}` : 'Out of Stock'}
                            </span>
                        </div>
                        <button class="btn btn-primary btn-full" 
                                onclick="addToCart('${product.id}')" 
                                ${product.stock <= 0 ? 'disabled' : ''}>
                            <i class="fas fa-shopping-cart"></i>
                            Add to Cart
                        </button>
                    </div>
                </div>
            `).join('');
            
            productsGrid.innerHTML = productsHTML;
        }
        
        // Load products on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadProducts();
        });
    </script>
</body>
</html>