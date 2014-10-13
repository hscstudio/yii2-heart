<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */

<?php
/* modify by yii2-heart */
?>
$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;
<?php
/* modify by yii2-heart */
?>

$this->title = 'Update #'.$model-><?= $generator->getNameAttribute() ?>;
$this->params['breadcrumbs'][] = ['label' => <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-view  panel panel-default">

   <div class="panel-heading"> 
		<div class="pull-right">
        <?= "<?=\n" ?> Html::a('<i class="fa fa-fw fa-arrow-left"></i> Back', ['index'], ['class' => 'btn btn-xs btn-primary']) ?>
		</div>
		<h1 class="panel-title"><?= "<?= " ?>Html::encode($this->title) ?></h1> 
	</div>
	<div class="panel-body">

		<!--
		<p>
			<?= "<?= " ?>Html::a(<?= $generator->generateString('Update') ?>, ['update', <?= $urlParams ?>], ['class' => 'btn btn-primary']) ?>
			<?= "<?= " ?>Html::a(<?= $generator->generateString('Delete') ?>, ['delete', <?= $urlParams ?>], [
				'class' => 'btn btn-danger',
				'data' => [
					'confirm' => <?= $generator->generateString('Are you sure you want to delete this item?') ?>,
					'method' => 'post',
				],
			]) ?>
		</p>
		-->
			<?= "<?= " ?>DetailView::widget([
				'model' => $model,
				'attributes' => [
		<?php
		if (($tableSchema = $generator->getTableSchema()) === false) {
			foreach ($generator->getColumnNames() as $name) {
				echo "            '" . $name . "',\n";
			}
		} else {
			foreach ($generator->getTableSchema()->columns as $column) {
				$format = $generator->generateColumnFormat($column);
				echo "            '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
			}
		}
		?>
				],
			]) ?>
	</div>
</div>
