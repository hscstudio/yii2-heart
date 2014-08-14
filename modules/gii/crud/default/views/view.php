<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

$urlParams = $generator->generateUrlParams();

echo "<?php\n";
?>

use yii\helpers\Html;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */

$this->title = $model-><?= $generator->getNameAttribute() ?>;
$this->params['breadcrumbs'][] = ['label' => <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu']=$menus;
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-view">

    <?= "<?= " ?>DetailView::widget([
        'model' => $model,
		'mode'=>DetailView::MODE_VIEW,
		'panel'=>[
			'heading'=>'<i class="fa fa-fw fa-globe"></i> '.'<?= Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass))) ?> # ' . $model->id,
			'type'=>DetailView::TYPE_DEFAULT,
		],
		'buttons1'=> Html::a('<i class="fa fa-fw fa-arrow-left"></i>',['index'],
						['class'=>'btn btn-xs btn-primary',
						 'title'=>'Back to Index',
						]).' '.
					 Html::a('<i class="fa fa-fw fa-trash-o"></i>',['#'],
						['class'=>'btn btn-xs btn-danger kv-btn-delete',
						 'title'=>'Delete', 'data-method'=>'post', 'data-confirm'=>'Are you sure you want to delete this item?']),
        'attributes' => [
<?php
if (($tableSchema = $generator->getTableSchema()) === false) {
    foreach ($generator->getColumnNames() as $name) {
        echo "            '" . $name . "',\n";
    }
} else {
    foreach ($generator->getTableSchema()->columns as $column) {
        $format = $generator->generateColumnFormat($column);
		$names = explode('_',$column->name);
		if(count($names>0) and in_array($names[0],['tbl','tb','ref'])){
			$new_name=substr($column->name,strlen($names[0]),strlen($column->name));
			$new_name=substr($new_name,0,strlen($new_name)-3);
			$new_name=\yii\helpers\Inflector::camelize($new_name);
			$new_name=lcfirst($new_name);
			echo "            [
				'attribute' => '".$column->name."',
				'value' => \$model->".$new_name."->name,
			],\n";
		}
        echo "            '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
    }
}
?>
        ],
    ]) ?>

</div>
