<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();

echo "<?php\n";
?>

use yii\helpers\Html;
use <?= $generator->indexWidgetType === 'grid' ? "yii\\grid\\GridView" : "yii\\widgets\\ListView" ?> as Gridview2;
<?php
/* modify by yii2-heart */
?>
use kartik\grid\GridView;
use yii\helpers\Url;
<?php
/* modify by yii2-heart */
?>

/* @var $this yii\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */

<?php
/* modify by yii2-heart */
?>
$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;
<?php
/* modify by yii2-heart */
?>
$this->title = <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-index">
	
<?php
/* modify by yii2-heart */
?>
<!--
    <h1><?= "<?= " ?>Html::encode($this->title) ?></h1>
<?php if(!empty($generator->searchModelClass)): ?>
<?= "    <?php " . ($generator->indexWidgetType === 'grid' ? "// " : "") ?>echo $this->render('_search', ['model' => $searchModel]); ?>
<?php endif; ?>

    <p>
        <?= "<?= " ?>Html::a(<?= $generator->generateString('Create {modelClass}', ['modelClass' => Inflector::camel2words(StringHelper::basename($generator->modelClass))]) ?>, ['create'], ['class' => 'btn btn-success']) ?>
    </p>
-->
<?php
/* modify by yii2-heart */
?>

<?php if ($generator->indexWidgetType === 'grid'): ?>
    <?= "<?= " ?>GridView::widget([
        'dataProvider' => $dataProvider,
        <?= !empty($generator->searchModelClass) ? "'filterModel' => \$searchModel,\n        'columns' => [\n" : "'columns' => [\n"; ?>
            ['class' => 'kartik\grid\SerialColumn'],

<?php
$count = 0;
if (($tableSchema = $generator->getTableSchema()) === false) {
    foreach ($generator->getColumnNames() as $name) {
		if (++$count < 6) {			
			echo "            '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";            
        } else {
            echo "            // '" . $name . "',\n";
        }
    }
} else {
    foreach ($tableSchema->columns as $column) {
        $format = $generator->generateColumnFormat($column);
		if (++$count < 6) {   
			$pos = strpos($column->name,'password');
			/* 
			 * Remove Prefix Tabel 
			 * tbl_ ref_ tb_		 
			 */
			$names = explode('_',$column->name);
			if(count($names>0) and in_array($names[0],['tbl','tb','ref'])){
				$new_name=substr($column->name,strlen($names[0]),strlen($column->name));
				$new_name=substr($new_name,0,strlen($new_name)-3);
				$new_name=\yii\helpers\Inflector::camelize($new_name);
				$new_name=lcfirst($new_name);
				echo "            /*
				[
					'attribute' => '".$column->name."',
					'value' => function (\$data) {
						return \$data->".$new_name."->name;
					}
				],
				*/\n";
			}
			else if($pos!==false){
				echo "            // '" . $column->name . "',\n";
				$count = $count-1;
			}
			else if($column->name=='id'){
				echo "            // '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
				$count = $count-1;
			}
			else if(in_array($column->name,['created','created_by','modified','modified_by'])){
				echo "            // '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
				$count = $count-1;
			}
			else{				
				if($format==='text'){
				echo "            
				[
					'attribute' => '".$column->name."',
					'vAlign'=>'middle',
					'hAlign'=>'center',
					'headerOptions'=>['class'=>'kv-sticky-column'],
					'contentOptions'=>['class'=>'kv-sticky-column'],					
				],\n";
				}
				else{
				echo "            '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
				}
			}
        } else {
            echo "            // '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
        }
    }
}
?>

            ['class' => 'kartik\grid\ActionColumn'],
        ],
		'panel' => [
			'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-globe"></i> '.Html::encode($this->title).'</h3>',
			'before'=>Html::a('<i class="fa fa-fw fa-plus"></i> Create ', ['create'], ['class' => 'btn btn-success']),
			'after'=>Html::a('<i class="fa fa-fw fa-repeat"></i> Reset Grid', Url::to(''), ['class' => 'btn btn-info']),
			'showFooter'=>false
		],
		'responsive'=>true,
		'hover'=>true,
    ]); ?>
<?php else: ?>
    <?= "<?= " ?>ListView::widget([
        'dataProvider' => $dataProvider
        'itemOptions' => ['class' => 'item'],
        'itemView' => function ($model, $key, $index, $widget) {
            return Html::a(Html::encode($model-><?= $nameAttribute ?>), ['view', <?= $urlParams ?>]);
        },
    ]) ?>
<?php endif; ?>

</div>
