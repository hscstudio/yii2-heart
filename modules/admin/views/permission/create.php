<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var mdm\admin\models\AuthItem $model
 */

$this->title = 'Create Role';
$this->params['breadcrumbs'][] = ['label' => 'Roles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginContent('@hscstudio/heart/modules/admin/views/layouts/column2.php'); ?>
<div class="auth-item-create">

	<?php echo $this->render('_form', [
		'model' => $model,
	]); ?>

</div>
<?php $this->endContent();