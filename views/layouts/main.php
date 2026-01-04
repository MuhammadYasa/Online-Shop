<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\helpers\Url;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);

// Font Awesome untuk icons
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');

// Custom CSS untuk sidebar
$this->registerCss("
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .wrapper {
        display: flex;
        width: 100%;
        align-items: stretch;
    }
    
    #sidebar {
        min-width: 250px;
        max-width: 250px;
        background: #2c3e50;
        color: #fff;
        transition: all 0.3s;
        height: 100vh;
        position: fixed;
        top: 56px;
        left: 0;
        z-index: 999;
        overflow-y: auto;
    }
    
    #sidebar.active {
        margin-left: -250px;
    }
    
    #sidebar .sidebar-header {
        padding: 20px;
        background: #34495e;
    }
    
    #sidebar ul.components {
        padding: 20px 0;
    }
    
    #sidebar ul li {
        padding: 10px;
        border-bottom: 1px solid #34495e;
    }
    
    #sidebar ul li a {
        padding: 10px;
        font-size: 1em;
        display: block;
        color: #ecf0f1;
        text-decoration: none;
        transition: all 0.3s;
    }
    
    #sidebar ul li a:hover,
    #sidebar ul li a.active {
        color: #fff;
        background: #34495e;
        border-radius: 5px;
    }
    
    #sidebar ul li a i {
        margin-right: 10px;
    }
    
    #content {
        width: 100%;
        padding: 20px;
        min-height: 100vh;
        transition: all 0.3s;
        margin-left: 250px;
        margin-top: 56px;
    }
    
    #content.active {
        margin-left: 0;
    }
    
    #sidebarCollapse {
        background: #34495e;
        border: none;
        color: #fff;
        padding: 10px 20px;
        cursor: pointer;
        margin-bottom: 20px;
    }
    
    #sidebarCollapse:hover {
        background: #2c3e50;
    }
    
    @media (max-width: 768px) {
        #sidebar {
            margin-left: -250px;
        }
        #sidebar.active {
            margin-left: 0;
        }
        #content {
            margin-left: 0;
        }
    }
");
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<header id="header">
    <?php
    NavBar::begin([
        'brandLabel' => '<i class="fas fa-store"></i> ' . Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => ['class' => 'navbar-expand-md navbar-dark bg-dark fixed-top'],
        'renderInnerContainer' => true,
        'innerContainerOptions' => ['class' => 'container-fluid']
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav ms-auto'],
        'items' => [
            Yii::$app->user->isGuest
                ? ['label' => '<i class="fas fa-sign-in-alt"></i> Login', 'url' => ['/site/login'], 'encode' => false]
                : '<li class="nav-item">'
                    . Html::beginForm(['/site/logout'], 'post')
                    . Html::submitButton(
                        '<i class="fas fa-sign-out-alt"></i> Logout (' . Yii::$app->user->identity->username . ')',
                        ['class' => 'nav-link btn btn-link logout', 'style' => 'color:#fff;']
                    )
                    . Html::endForm()
                    . '</li>'
        ],
        'encodeLabels' => false,
    ]);
    NavBar::end();
    ?>
</header>

<div class="wrapper">
    <?php if (!Yii::$app->user->isGuest): ?>
    <!-- SIDEBAR NAVIGATION (Hanya tampil jika sudah login) -->
    <nav id="sidebar">
        <div class="sidebar-header">
            <h3><i class="fas fa-tachometer-alt"></i> Admin Panel</h3>
            <small>Olshop Management</small>
        </div>

        <ul class="list-unstyled components">
            <li>
                <a href="<?= Url::to(['/dashboard/index']) ?>" class="<?= Yii::$app->controller->id === 'dashboard' ? 'active' : '' ?>">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="<?= Url::to(['/category/index']) ?>" class="<?= Yii::$app->controller->id === 'category' ? 'active' : '' ?>">
                    <i class="fas fa-folder"></i> Kategori
                </a>
            </li>
            <li>
                <a href="<?= Url::to(['/product/index']) ?>" class="<?= Yii::$app->controller->id === 'product' ? 'active' : '' ?>">
                    <i class="fas fa-box"></i> Produk
                </a>
            </li>
            <li>
                <a href="<?= Url::to(['/tag/index']) ?>" class="<?= Yii::$app->controller->id === 'tag' ? 'active' : '' ?>">
                    <i class="fas fa-tags"></i> Tag
                </a>
            </li>
            <li>
                <a href="<?= Url::to(['/site/index']) ?>">
                    <i class="fas fa-globe"></i> Lihat Website
                </a>
            </li>
        </ul>
    </nav>
    <?php endif; ?>

    <!-- CONTENT AREA -->
    <div id="content" class="<?= Yii::$app->user->isGuest ? 'active' : '' ?>">
        <?php if (!Yii::$app->user->isGuest): ?>
        <button type="button" id="sidebarCollapse" class="btn btn-sm">
            <i class="fas fa-bars"></i> Toggle Menu
        </button>
        <?php endif; ?>

        <div class="container-fluid">
            <?php if (!empty($this->params['breadcrumbs'])): ?>
                <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
            <?php endif ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </div>
</div>

<footer id="footer" class="bg-light py-3" style="<?= !Yii::$app->user->isGuest ? 'margin-left: 250px;' : '' ?>">
    <div class="container">
        <div class="row text-muted">
            <div class="col-md-6 text-center text-md-start">&copy; Olshop <?= date('Y') ?></div>
            <div class="col-md-6 text-center text-md-end"><?= Yii::powered() ?></div>
        </div>
    </div>
</footer>

<?php
// JavaScript untuk toggle sidebar
$this->registerJs("
    $('#sidebarCollapse').on('click', function () {
        $('#sidebar, #content').toggleClass('active');
    });
");
?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
