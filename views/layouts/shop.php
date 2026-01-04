<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\helpers\Url;
use app\models\Cart;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);

// Font Awesome
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');

// Get cart count
$sessionId = Yii::$app->session->getId();
$customerId = Yii::$app->session->get('customer_id'); // Ambil dari session
$cartItems = Cart::getItems($sessionId, $customerId);
$cartCount = count($cartItems);
$cartBadge = $cartCount > 99 ? '99+' : $cartCount;

// Check if customer is logged in
$isCustomerLoggedIn = !empty(Yii::$app->session->get('customer_id'));
$customerName = Yii::$app->session->get('customer_name');

// Custom CSS
$this->registerCss("
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f8f9fa;
    }
    
    .shop-navbar {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .shop-navbar .navbar-brand {
        font-size: 1.5em;
        font-weight: bold;
        color: #fff !important;
    }
    
    .shop-navbar .nav-link {
        color: #fff !important;
        margin: 0 10px;
        transition: all 0.3s;
    }
    
    .shop-navbar .nav-link:hover {
        color: #ffd700 !important;
    }
    
    .cart-badge-inline {
        background: #dc3545;
        color: white;
        border-radius: 12px;
        padding: 2px 8px;
        font-size: 11px;
        font-weight: bold;
        margin-right: 8px;
        display: inline-block;
        min-width: 22px;
        text-align: center;
        line-height: 1.4;
        box-shadow: 0 2px 4px rgba(220, 53, 69, 0.4);
    }
    
    footer {
        background: #2c3e50;
        color: #ecf0f1;
        padding: 40px 0 20px;
        margin-top: 50px;
    }
");
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <title><?= Html::encode($this->title) ?> - Olshop</title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark shop-navbar">
    <div class="container">
        <a class="navbar-brand" href="<?= Url::to(['site/index']) ?>">
            <i class="fas fa-shopping-bag"></i> My Olshop
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= Url::to(['site/index']) ?>">
                        <i class="fas fa-home"></i> Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= Url::to(['cart/index']) ?>">
                        <?php if ($cartCount > 0): ?>
                            <span class="cart-badge-inline"><?= $cartBadge ?></span>
                        <?php endif; ?>
                        <i class="fas fa-shopping-cart"></i> Keranjang
                    </a>
                </li>
                <?php if (!$isCustomerLoggedIn): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= Url::to(['site/customer-login']) ?>">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= Url::to(['site/register']) ?>">
                            <i class="fas fa-user-plus"></i> Register
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= Url::to(['checkout/orders']) ?>">
                            <i class="fas fa-box"></i> Pesanan Saya
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user"></i> <?= Html::encode($customerName) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="<?= Url::to(['site/profile']) ?>">
                                    <i class="fas fa-user-edit"></i> Edit Profile
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="<?= Url::to(['site/customer-logout']) ?>">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- CONTENT -->
<div class="container py-4">
    <?= Alert::widget() ?>
    <?= $content ?>
</div>

<!-- FOOTER -->
<footer>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h5><i class="fas fa-shopping-bag"></i> My Olshop</h5>
                <p>Belanja online mudah, aman, dan terpercaya.</p>
            </div>
            <div class="col-md-4">
                <h5>Layanan</h5>
                <ul class="list-unstyled">
                    <li><a href="#" class="text-white">Cara Belanja</a></li>
                    <li><a href="#" class="text-white">Pembayaran</a></li>
                    <li><a href="#" class="text-white">Pengiriman</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h5>Hubungi Kami</h5>
                <p>
                    <i class="fas fa-phone"></i> 08123456789<br>
                    <i class="fas fa-envelope"></i> info@myolshop.com
                </p>
            </div>
        </div>
        <hr style="border-color: #ecf0f1;">
        <div class="text-center">
            <small>&copy; <?= date('Y') ?> My Olshop. All rights reserved.</small>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
