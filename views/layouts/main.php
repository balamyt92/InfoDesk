<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\AppAsset;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'InfoDesk',
        'brandUrl'   => Yii::$app->homeUrl,
        'options'    => [
            'class' => 'navbar-default navbar-fixed-top',
        ],
        'innerContainerOptions' => [
            'class' => 'container-fluid',
        ],
    ]);
    $menuItems = [
        ['label' => 'Call-center', 'url' => ['/site/index']],
    ];
    if (Yii::$app->user->isGuest) {
        $loginMenu[] = ['label' => 'Войти', 'url' => ['/site/login']];
    } else {
        if (\app\models\User::isUserAdmin(Yii::$app->user->identity->username)) {
            $menuItems[] = ['label' => 'Фирмы', 'url' => ['/firms/index']];
            $menuItems[] = ['label' => 'Марки', 'url' => ['/marks/index']];
            $menuItems[] = ['label' => 'Статистика', 'url' => ['/statistic/index']];
            $menuItems[] = ['label' => 'Текстовые блоки', 'url' => ['/text-blocks/index']];
        }

        $loginMenu[] = '<li>'
            .Html::beginForm(['/site/logout'], 'post')
            .Html::submitButton(
                'Сменить пользователя ('.Yii::$app->user->identity->username.')',
                ['class' => 'btn btn-link', 'style' => 'height:50px']
            )
            .Html::endForm()
            .'</li>';
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav nav-pills'],
        'items'   => $menuItems,
    ]);

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items'   => $loginMenu,
    ]);
    NavBar::end();
    ?>

    <div class="container-fluid">
        <div class="row">
            <?php echo \app\common\Alert::widget(); ?>
        </div>
        <?= $content ?>
    </div>
</div>

<?php $this->endBody() ?>

</body>
</html>
<?php $this->endPage() ?>
