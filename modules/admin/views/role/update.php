<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var mdm\admin\models\AuthItem $model
 */
$this->title = 'Update Role: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Roles', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->name]];
$this->params['breadcrumbs'][] = 'Update';
$controller = $this->context;
$menus = $controller->module->menus;
$this->params['sideMenu']=$menus;
?>
<?php $this->beginContent('@hscstudio/heart/views/layouts/column2module.php'); ?>
<div class="auth-item-update">
	<?php
	echo $this->render('_form', [
		'model' => $model,
	]);
	?>
</div>
<?php $this->endContent();