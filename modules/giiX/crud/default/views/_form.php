<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

$model = new $generator->modelClass();
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->attributes();
}

echo "<?php\n";
?>

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form">
<div class="panel panel-default">
	<div class="panel-heading">
		<div class="pull-right">
		<?= '<?=' ?> Html::a('<i class="fa fa-arrow-left"></i>',['index'],
						['class'=>'btn btn-xs btn-primary',
						 'title'=>'Back to Index',
						]) ?>
		</div>
		<i class="fa fa-fw fa-globe"></i> 
		<?= StringHelper::basename($generator->modelClass) ?>
	</div>
	<div style="margin:10px">
    <?= "<?php " ?>$form = ActiveForm::begin([
		'type' => ActiveForm::TYPE_HORIZONTAL,
		'options'=>['enctype'=>'multipart/form-data']
	]); ?>
	<?= "<?=" ?> $form->errorSummary($model) ?>
	
<?php foreach ($safeAttributes as $attribute) {
    echo "    <?= " . $generator->generateActiveField($attribute) . " ?>\n\n";
} ?>
    <div class="form-group">
		<label class="col-md-2 control-label"></label>
		<div class="col-md-10">
        <?= "<?= " ?>Html::submitButton(
			$model->isNewRecord ? '<span class="fa fa-fw fa-save"></span> '.<?= $generator->generateString('Create') ?> : '<span class="fa fa-fw fa-save"></span> '.<?= $generator->generateString('Update') ?>, 
			['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		</div>
	</div>
	
    <?= "<?php " ?>ActiveForm::end(); ?>
	</div>
</div>
</div>
