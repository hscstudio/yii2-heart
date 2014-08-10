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
use <?= $generator->indexWidgetType === 'grid' ? "kartik\\grid\\GridView" : "yii\\widgets\\ListView" ?>;
use yii\bootstrap\Dropdown;

/* @var $this yii\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>;
$this->params['breadcrumbs'][] = $this->title;

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu']=$menus;
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-index">

    <!-- <h1><?= "<?= " ?>Html::encode($this->title) ?></h1> -->
<?php if(!empty($generator->searchModelClass)): ?>
<?= "    <?php " . ($generator->indexWidgetType === 'grid' ? "// " : "") ?>echo $this->render('_search', ['model' => $searchModel]); ?>
<?php endif; ?>
	<!--
    <p>
        <?= "<?= " ?>Html::a(<?= $generator->generateString('Create {modelClass}', ['modelClass' => Inflector::camel2words(StringHelper::basename($generator->modelClass))]) ?>, ['create'], ['class' => 'btn btn-success']) ?>
    </p>
	-->
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
            echo "            '" . $name . "',\n";
        } else {
            echo "            // '" . $name . "',\n";
        }
    }
} else {
	$count=0;
    foreach ($tableSchema->columns as $column) {
        $format = $generator->generateColumnFormat($column);
		if ($count < 6) {
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
			else if($column->name=='id'){
				echo "            // '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
			}
			else if(in_array($column->name,['created','createdBy','modified','modifiedBy','deleted','deletedBy'])){
				echo "            // '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
			}
			else{				
				/*if($column->type=='date'){
				echo "            
				[
					'class' => 'kartik\grid\EditableColumn',
					'attribute' => '".$column->name."',
					'pageSummary' => 'Page Total',
					'vAlign'=>'middle',
					'headerOptions'=>['class'=>'kv-sticky-column'],
					'contentOptions'=>['class'=>'kv-sticky-column'],
					'editableOptions'=>[
						'header'=>'".ucfirst($column->name)."', 
						'size'=>'md',
						'formOptions'=>['action'=>\yii\helpers\Url::to('/editable')],
						'inputType' => 'Editable::INPUT_DATE',
						'options'=>[
							'pluginOptions' => [
								'format' => 'dd-M-yyyy',
								'todayHighlight' => true
							]
						]	
					]
				],\n";
				}
				else if($format==='boolean'){
				echo "            
				[
					'class' => '\kartik\grid\BooleanColumn',
					'trueLabel' => 'Yes', 
					'falseLabel' => 'No'
				],\n";
				}
				else */
				if($format==='text'){
				echo "            
				[
					'class' => 'kartik\grid\EditableColumn',
					'attribute' => '".$column->name."',
					//'pageSummary' => 'Page Total',
					'vAlign'=>'middle',
					'headerOptions'=>['class'=>'kv-sticky-column'],
					'contentOptions'=>['class'=>'kv-sticky-column'],
					'editableOptions'=>['header'=>'".ucfirst($column->name)."', 'size'=>'md','formOptions'=>['action'=>\yii\helpers\Url::to('editable')]]
				],\n";
				}
				else{
				echo "            '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
				}
				$count++;
			}				
		}
        /*if (++$count < 6) {
            echo "            '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
        } else {
            echo "            // '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
        }*/
    }
}
?>

            ['class' => 'kartik\grid\ActionColumn'],
        ],
		'panel' => [
			//'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-globe"></i> <?= Inflector::camel2words(StringHelper::basename($generator->modelClass)); ?></h3>',
			'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-globe"></i></h3>',
			//'type'=>'primary',
			'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Create <?= Inflector::camel2words(StringHelper::basename($generator->modelClass)); ?>', ['create'], ['class' => 'btn btn-success']),
			'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset Grid', ['index'], ['class' => 'btn btn-info']),
			'showFooter'=>false
		],
		'responsive'=>true,
		'hover'=>true,
    ]); ?>
	<?= "<?php " ?>	
	echo Html::beginTag('div', ['class'=>'row']);
		echo Html::beginTag('div', ['class'=>'col-md-2']);
			echo Html::beginTag('div', ['class'=>'dropdown']);
				echo Html::button('PHPExcel <span class="caret"></span></button>', 
					['type'=>'button', 'class'=>'btn btn-default', 'data-toggle'=>'dropdown']);
				echo Dropdown::widget([
					'items' => [
						['label' => 'EXport XLSX', 'url' => ['php-excel?filetype=xlsx&template=yes']],
						['label' => 'EXport XLS', 'url' => ['php-excel?filetype=xls&template=yes']],
						['label' => 'Export PDF', 'url' => ['php-excel?filetype=pdf&template=no']],
					],
				]); 
			echo Html::endTag('div');
		echo Html::endTag('div');
	
		echo Html::beginTag('div', ['class'=>'col-md-2']);
			echo Html::beginTag('div', ['class'=>'dropdown']);
				echo Html::button('OpenTBS <span class="caret"></span></button>', 
					['type'=>'button', 'class'=>'btn btn-default', 'data-toggle'=>'dropdown']);
				echo Dropdown::widget([
					'items' => [
						['label' => 'EXport DOCX', 'url' => ['open-tbs?filetype=docx']],
						['label' => 'EXport ODT', 'url' => ['open-tbs?filetype=odt']],
						['label' => 'EXport XLSX', 'url' => ['open-tbs?filetype=xlsx']],
					],
				]); 
			echo Html::endTag('div');
		echo Html::endTag('div');
		
		echo Html::beginTag('div', ['class'=>'col-md-8']);
			$form = \yii\bootstrap\ActiveForm::begin([
				'options'=>['enctype'=>'multipart/form-data'],
				'action'=>['import'],
			]);
			echo \kartik\widgets\FileInput::widget([
				'name' => 'importFile', 
				//'options' => ['multiple' => true], 
				'pluginOptions' => [
					'previewFileType' => 'any',
					'uploadLabel'=>"Import Excel",
				]
			]);
			\yii\bootstrap\ActiveForm::end();
		echo Html::endTag('div');
		
	echo Html::endTag('div');
	?>
<?php else: ?>
    <?= "<?= " ?>ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'itemView' => function ($model, $key, $index, $widget) {
            return Html::a(Html::encode($model-><?= $nameAttribute ?>), ['view', <?= $urlParams ?>]);
        },
    ]) ?>
<?php endif; ?>

</div>
