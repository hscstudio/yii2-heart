<?php
use yii\helpers\Html;
use hscstudio\heart\widgets\NavSide;
use hscstudio\heart\widgets\Breadcrumbs;
/**
 * @var \yii\web\View $this
 * @var string $content
 */
?>

<div class="wrapper row-offcanvas row-offcanvas-left">
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="left-side sidebar-offcanvas">                
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
			<?= NavSide::widget([
				'items' => [
					['label' => Yii::t('user', 'Profile'), 'url' => ['/user/settings/profile']],
					['label' => Yii::t('user', 'Email'), 'url' => ['/user/settings/email']],
					['label' => Yii::t('user', 'Password'), 'url' => ['/user/settings/password']],
					['label' => Yii::t('user', 'Networks'), 'url' => ['/user/settings/networks']],
				]
			]) ?>
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Right side column. Contains the navbar and content of the page -->
    <aside class="right-side">   
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1><?= Html::encode($this->title) ?></h1>
        </section>
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <!-- Main content -->
        <section class="content">
            <?= $content ?>
        </section><!-- /.content -->
    </aside><!-- /.right-side -->
</div><!-- ./wrapper -->