<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Modal;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\helpers\Url;

AppAsset::register($this);

// $this->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.0/font/bootstrap-icons.css');

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>
<nav class="py-2 bg-body-tertiary border-bottom">
    <div class="container-fluid d-flex flex-wrap">
        <?php 
            echo Nav::widget([
                'options' => ['class' => 'nav me-auto'],
                'items' => [
                    // [
                    //     'label' => 'Home', 'url' => ['/site/index'],
                    //     'linkOptions' => ['class' => 'nav-link link-body-emphasis px-2'],
                    // ],
                    [
                        'label' => 'Acerca de', 'url' => ['/site/about'],
                        'linkOptions' => ['class' => 'nav-link link-body-emphasis px-2'],
                    ],
                ],
                'encodeLabels' => false,
            ]);
            echo Nav::widget([
                'options' => ['class' => 'nav'],
                'items' => [
                    [
                        'label' => 'Ayuda', 'url' => ['/site/help'],
                        'linkOptions' => ['class' => 'nav-link link-body-emphasis px-2'],
                    ],
                    [
                        'label' => 'Contacto', 'url' => ['/site/contact'],
                        'linkOptions' => ['class' => 'nav-link link-body-emphasis px-2'],
                    ],
                    // Yii::$app->user->isGuest
                    //     ? ['label' => 'Login', 'url' => ['/site/login']]
                    //     : '<li class="nav-item">'
                    //         . Html::beginForm(['/site/logout'])
                    //         . Html::submitButton(
                    //             'Logout (' . Yii::$app->user->identity->username . ')',
                    //             ['class' => 'nav-link btn btn-link logout']
                    //         )
                    //         . Html::endForm()
                    //         . '</li>'
                ],
                'encodeLabels' => false,
            ]);
        ?>
    </div>
</nav>

<header class="px-0 border-bottom"> 
    <div class="container-fluid px-5">
      <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
        <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 link-body-emphasis text-decoration-none">
          <!-- <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap"><use xlink:href="#bootstrap"></use></svg> -->
          <img class="bi me-2" height="32" role="img" src="<?= Yii::getAlias('@web/images/logos/Logo-header.png') ?>" />
        </a>
        <?php 
        echo Nav::widget([
            'options' => ['class' => 'nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0 ms-3'],
            'items' => [
                [
                    'label' => '<i class="bi bi-house d-block mx-auto mb-1" style="font-size: 2rem; color: cornflowerblue; text-align:center;"></i> Inicio',
                    'url' => ['/site/index'],
                    'linkOptions' => ['class' => 'nav-link link-body-emphasis px-2'],
                ],
            ],
            'encodeLabels' => false,
        ]);
        ?>
        <?php if (!Yii::$app->user->isGuest) : ?>
            <div class="dropdown text-end">
              <a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <!-- <img src="https://github.com/mdo.png" alt="mdo" width="32" height="32" class="rounded-circle"> -->
                <img src="<?= Yii::$app->user->identity->avatarUrl ?>" alt="mdo" width="32" height="32" class="rounded-circle">
                <?= Yii::$app->user->identity->username ?>
              </a>
              <ul class="dropdown-menu text-small">
                <!-- <li><a class="dropdown-item" href="#">New project...</a></li> -->
                <li><a class="dropdown-item" href="<?= Url::toRoute(['/site/profile'])?>">Perfil</a></li>
                <li><a class="dropdown-item" href="<?= Url::toRoute(['/site/settings'])?>">Ajustes</a></li>
                <li><hr class="dropdown-divider"></li>
                <?php 
                echo '<li>' . Html::beginForm(['/site/logout'])
                    . Html::submitButton(
                        'Cerrar sesion',
                        ['class' => 'dropdown-item']
                    )
                    . Html::endForm() . '</li>';
                ?>
              </ul>
            </div>
            <?php if (Yii::$app->user->can('admin')) : ?>
                <div class="dropdown text-end px-2">
                    <a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-gear-wide-connected"></i> AdminPanel
                    </a>
                    <ul class="dropdown-menu text-small">
                        <!-- <li><a class="dropdown-item" href="#">New project...</a></li> -->
                        <li>
                            <a class="dropdown-item" href="<?= Url::toRoute(['/users/index'])?>">
                                <i class="bi bi-people"></i> Usuarios
                            </a>
                        </li>
                        <!-- <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?= Url::toRoute(['/site/admin-options'])?>">Opciones</a></li> -->
                    </ul>
                </div>
            <?php endif ?>
        <?php endif ?>
      </div>
    </div>
    
    <div class="px-2 py-2 border- text-bg-dark">
      <div class="container d-flex flex-wrap justify-content-center">
        <form class="col-12 col-lg-auto mb-2 mb-lg-0 me-lg-auto" role="search">
          <input type="search" class="form-control" placeholder="Search..." aria-label="Search">
        </form>
        <div class="text-end">
            <?php 
                if (Yii::$app->user->isGuest) {
                    echo '<a href="'.Url::toRoute(['/site/login']).'" class="btn btn-light text-dark me-2">Ingresar</a>';
                    echo '<a href="'.Url::toRoute(['/site/signup']).'" class="btn btn-primary">Registrarte</a>';
                }
                else {
                    // echo Html::beginForm(['/site/logout'])
                    // . Html::submitButton(
                    //     'Logout (' . Yii::$app->user->identity->username . ')',
                    //     ['class' => 'btn btn-secondary']
                    // )
                    // . Html::endForm();
                    ?>
                    <?php 
                }

                // Modal::begin([
                //     'title' => 'Hello world',
                //     'toggleButton' => ['label' => 'Inicio rapido', 'class' => 'btn btn-light text-dark me-2' ],
                // ]);
                // echo Html::tag('iframe', '', ['src' => '/site/login', "style" => "width: 100%;height: calc(75vh);border: 0;padding: 0;margin: 0;"]);
                // Modal::end();
            ?>
        </div>
      </div>
    </div>
</header>

<!-- <header id="header">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => ['class' => 'navbar-expand-md navbar-dark bg-dark fixed-top']
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav'],
        'items' => [
            ['label' => 'Home', 'url' => ['/site/index']],
            ['label' => 'About', 'url' => ['/site/about']],
            ['label' => 'Contact', 'url' => ['/site/contact']],
            // Yii::$app->user->isGuest
            //     ? ['label' => 'Login', 'url' => ['/site/login']]
            //     : '<li class="nav-item">'
            //         . Html::beginForm(['/site/logout'])
            //         . Html::submitButton(
            //             'Logout (' . Yii::$app->user->identity->username . ')',
            //             ['class' => 'nav-link btn btn-link logout']
            //         )
            //         . Html::endForm()
            //         . '</li>'
        ]
    ]);
    NavBar::end();
    ?>
</header> -->

<main id="main" class="flex-shrink-0" role="main">
    <div class="container">
        <?php if (!empty($this->params['breadcrumbs'])): ?>
            <div class="bg-light rounded-3 mt-3 pb-1 p-3 mb-2">
                <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
            </div>
        <?php endif ?>
        <?= Alert::widget() ?>
    </div>
    <?= $content ?>
</main>

<footer id="footer" class="mt-auto py-3 bg-light">
    <div class="container">
        <div class="row text-muted">
            <div class="col-md-6 text-center text-md-start">&copy; PACMEC <?= date('Y') ?></div>
            <div class="col-md-6 text-center text-md-end"><?php // = Yii::powered() ?>Powered by FelipheGomez</div>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
