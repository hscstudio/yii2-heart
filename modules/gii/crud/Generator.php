<?php
namespace hscstudio\heart\modules\gii\crud;

use Yii;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Schema;
use yii\gii\CodeFile;
use yii\helpers\Inflector;
use yii\helpers\VarDumper;
use yii\web\Controller;

/**
 * Generates CRUD
 *
 * @property array $columnNames Model column names. This property is read-only.
 * @property string $controllerID The controller ID (without the module ID prefix). This property is
 * read-only.
 * @property array $searchAttributes Searchable attributes. This property is read-only.
 * @property boolean|\yii\db\TableSchema $tableSchema This property is read-only.
 * @property string $viewPath The action view file path. This property is read-only.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class Generator extends \yii\gii\generators\crud\Generator
{

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'CRUD Generator (Heart)';
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return 'This generator generates a controller and views that implement CRUD (Create, Read, Update, Delete)
            operations for the specified data model.';
    }

    /**
     * @inheritdoc
     */
    public function requiredTemplates()
    {
        return ['controller.php'];
    }

    /**
     * Generates code for active field
     * @param string $attribute
     * @return string
     */
    public function generateActiveField($attribute)
    {
        $tableSchema = $this->getTableSchema();
        if ($tableSchema === false || !isset($tableSchema->columns[$attribute])) {
            if (preg_match('/^(password|pass|passwd|passcode)$/i', $attribute)) {
                return "\$form->field(\$model, '$attribute')->passwordInput()";
            } else {
                return "\$form->field(\$model, '$attribute')";
            }
        }
        $column = $tableSchema->columns[$attribute];
		
		/*
		Hide created, modified, createdBy, modifiedBy, deleted, deletedBy
		*/
		if (preg_match('/^(created)$/i', $column->name) && $column->type === 'datetime') return '""//'.$column->name.'';
		if (preg_match('/^(modified)$/i', $column->name) && $column->type === 'datetime') return '""//'.$column->name.'';
		if (preg_match('/^(deleted)$/i', $column->name) && $column->type === 'datetime') return '""//'.$column->name.'';
		if (preg_match('/^(createdBy)$/i', $column->name) && $column->type === 'integer') return '""//'.$column->name.'';
		if (preg_match('/^(modifiedBy)$/i', $column->name) && $column->type === 'integer') return '""//'.$column->name.'';
		if (preg_match('/^(deletedBy)$/i', $column->name) && $column->type === 'integer') return '""//'.$column->name.'';
		if ($column->type === 'smallint' or ($column->type === 'integer' && $column->size<=3)){
		    if(preg_match('/^(gender)$/i', $column->name)){
				return "\$form->field(\$model, '$attribute')->widget(\kartik\widgets\SwitchInput::classname(), [
					'pluginOptions' => [
						'onText' => 'Male',
						'offText' => 'Female',
					]
				])";
				
			}
			if($column->size==1){
				return "\$form->field(\$model, '$attribute')->widget(\kartik\widgets\SwitchInput::classname(), [
					'pluginOptions' => [
						'onText' => 'On',
						'offText' => 'Off',
					]
				])";
			}
			$dropDownOptions = ['0'=>'Option1','1'=>'Option2','2'=>'Option3'];		
			if(!empty($dropDownOptions)){
				return "\$form->field(\$model, '$attribute')->dropDownList("
					. preg_replace("/\n\s*/", ' ', VarDumper::export($dropDownOptions)).", ['prompt' => 'Choose ".$column->name."'])";
			}
		}
		
        if ($column->phpType === 'boolean') {
            return "\$form->field(\$model, '$attribute')->widget(\kartik\widgets\SwitchInput::classname(), [])";
        } elseif ($column->type === 'text') {
            return "\$form->field(\$model, '$attribute')->textarea(['rows' => 6])";
        } else {			
			if (preg_match('/^(email|mail)$/i', $column->name)) {
				return "\$form->field(\$model, '$attribute', [
					 'inputTemplate' => '<div class=\"input-group\"><span class=\"input-group-addon\">@</span>{input}</div>',
				 ]);";
			}
			
			// Usage with model and Active Form (with no default initial value)
			if ($column->type === 'date'){
				return "\$form->field(\$model, '$attribute')->widget(\kartik\datecontrol\DateControl::classname(), [
					'options' => ['placeholder' => 'Enter date ...'],
					'type' => \kartik\datecontrol\DateControl::FORMAT_DATE,
				]);";
			}
			
			if ($column->type === 'datetime'){
				return "\$form->field(\$model, '$attribute')->widget(\kartik\datecontrol\DateControl::classname(), [
					'options' => ['placeholder' => 'Enter datetime ...'],
					'type' => \kartik\datecontrol\DateControl::FORMAT_DATETIME,
				]);";
			}
			
			if ($column->type === 'time'){
				return "\$form->field(\$model, '$attribute')->widget(\kartik\datecontrol\DateControl::classname(), [
					'options' => ['placeholder' => 'Enter time ...'],
					'type' => \kartik\datecontrol\DateControl::FORMAT_TIME,
				]);";
			}
			
            if (preg_match('/^(password|pass|passwd|passcode)$/i', $column->name)) {
                $input = 'passwordInput';
            } else {
                $input = 'textInput';
            }
			
            if (is_array($column->enumValues) && count($column->enumValues) > 0) {
                $dropDownOptions = [];
                foreach ($column->enumValues as $enumValue) {
                    $dropDownOptions[$enumValue] = Inflector::humanize($enumValue);
                }
                return "\$form->field(\$model, '$attribute')->dropDownList("
                    . preg_replace("/\n\s*/", ' ', VarDumper::export($dropDownOptions)).", ['prompt' => ''])";			
            } elseif ($column->phpType !== 'string' || $column->size === null) {
                return "\$form->field(\$model, '$attribute')->$input()";
			} elseif ($column->phpType == 'string' && $column->size === 255 && 
					preg_match('/^(document|document1|document2|photo|image|picture|file)$/i', $column->name)) {
                return "\$form->field(\$model, '$attribute')->widget(\kartik\widgets\FileInput::classname(), [
					'pluginOptions' => ['previewFileType' => 'any']
					]);";
            } else {
                return "\$form->field(\$model, '$attribute')->$input(['maxlength' => $column->size])";
            }
        }
    }

}