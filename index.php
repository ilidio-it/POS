<?php
session_start();
require_once 'config/database.php';
require_once 'includes/auth.php';

// Check if user is logged in
if (!isLoggedIn()) {
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
    <title>Cantina Smart Sales - Dashboard</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/components.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main class="container">
        <div class="dashboard-header">
            <h1>Dashboard</h1>
            <p>Overview of your canteen operations</p>
        </div>

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
        <div id="productsGrid" class="products-grid">
            <!-- Products will be loaded here via AJAX -->
        </div>

        <!-- Dashboard Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-content">
                    <h3>Today's Sales</h3>
                    <p class="stat-value" id="todaySales">Loading...</p>
                    <p class="stat-change">15% increase from yesterday</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-content">
                    <h3>Total Revenue</h3>
                    <p class="stat-value" id="totalRevenue">Loading...</p>
                    <p class="stat-change">8% increase from last week</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-box"></i>
                </div>
                <div class="stat-content">
                    <h3>Products</h3>
                    <p class="stat-value" id="totalProducts">Loading...</p>
                    <p class="stat-change">5 new products added</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <h3>Active Users</h3>
                    <p class="stat-value" id="activeUsers">Loading...</p>
                    <p class="stat-change">2 users online</p>
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
            <button id="clearCart" class="btn btn-outline">Clear Cart</button>
            <button id="completeSale" class="btn btn-primary">Complete Sale</button>
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
</body>
</html>