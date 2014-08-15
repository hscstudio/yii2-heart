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
	public $layout;
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
     * @inheritdoc
     */
    public function generate()
    {
        $controllerFile = Yii::getAlias('@' . str_replace('\\', '/', ltrim($this->controllerClass, '\\')) . '.php');

        $files = [
            new CodeFile($controllerFile, $this->render('controller.php')),
        ];

        if (!empty($this->searchModelClass)) {
            $searchModel = Yii::getAlias('@' . str_replace('\\', '/', ltrim($this->searchModelClass, '\\') . '.php'));
            $files[] = new CodeFile($searchModel, $this->render('search.php'));
        }

        $viewPath = $this->getViewPath();
        $templatePath = $this->getTemplatePath() . '/views';
        foreach (scandir($templatePath) as $file) {
            if (empty($this->searchModelClass) && $file === '_search.php') {
                continue;
            }
            if (is_file($templatePath . '/' . $file) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                $files[] = new CodeFile("$viewPath/$file", $this->render("views/$file"));
            }
        }
		
        return $files;
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
		
		$names = explode('_',$column->name);
		if(count($names>0) and in_array($names[0],['tbl','tb','ref'])){
			$new_name=substr($column->name,strlen($names[0]),strlen($column->name));
			$new_name=substr($new_name,0,strlen($new_name)-3);
			$new_name=\yii\helpers\Inflector::camelize($new_name);
			//$new_name=lcfirst($new_name);
			return "'' ?>\n
			<?php
			\$data = ArrayHelper::map(\backend\models\\".$new_name."::find()->select(['id','name'])->asArray()->all(), 'id', 'name');
			echo \$form->field(\$model, '$attribute')->widget(Select2::classname(), [
				'data' => \$data,
				'options' => ['placeholder' => 'Choose ".$new_name." ...'],
				'pluginOptions' => [
				'allowClear' => true
				],
			]);";
		}
		else if ($column->type === 'smallint' or ($column->type === 'integer' && $column->size<=3)){
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

			$input = 'textInput';
			return "\$form->field(\$model, '$attribute')->$input(['maxlength' => $column->size])";
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
					'type' => \kartik\datecontrol\DateControl::FORMAT_DATE,
				]);";
			}
			
			if ($column->type === 'datetime'){
				return "\$form->field(\$model, '$attribute')->widget(\kartik\datecontrol\DateControl::classname(), [
					'type' => \kartik\datecontrol\DateControl::FORMAT_DATETIME,
				]);";
			}
			
			if ($column->type === 'time'){
				return "\$form->field(\$model, '$attribute')->widget(\kartik\datecontrol\DateControl::classname(), [
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
					'pluginOptions' => [
						'previewFileType' => 'any',
						'showUpload' => false,
						]
					]);";
            } else {
                return "\$form->field(\$model, '$attribute')->$input(['maxlength' => $column->size])";
            }
        }
    }
}