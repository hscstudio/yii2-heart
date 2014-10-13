<?php
/**
 * This is the template for generating the model class of a specified table.
 */

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\model\Generator */
/* @var $tableName string full table name */
/* @var $className string class name */
/* @var $tableSchema yii\db\TableSchema */
/* @var $labels string[] list of attribute labels (name => label) */
/* @var $rules string[] list of validation rules */
/* @var $relations array list of relations (name => relation declaration) */

echo "<?php\n";
?>

namespace <?= $generator->ns ?>;

use Yii;
<?php 
/* 
	Auto Behaviour 
	==============
	+ AutoTimeStamp (DateTime)
		Only for :
		- field created with type datetime
		- field modified with type datetime
	+ AutoUserId
		Only for :
		- field createdBy with type integer
		- field modifiedBy with type integer
*/
$created = false;
$modified = false;
$createdBy = false;
$modifiedBy = false;
?>
<?php foreach ($tableSchema->columns as $column): ?>
	<?php
	/* Auto Behaviour */
	if (preg_match('/^(created)$/i', $column->name) && $column->type === 'datetime') {
		$created=true;
	}
	if (preg_match('/^(modified)$/i', $column->name) && $column->type === 'datetime') {
		$modified = true;
	}
	if (preg_match('/^(createdBy)$/i', $column->name) && $column->type === 'integer') {
		$createdBy = true;
	}	
	if (preg_match('/^(modifiedBy)$/i', $column->name) && $column->type === 'integer') {
		$modifiedBy = true;
	}
	?>
<?php endforeach; ?>

<?php if($created or $modified): ?>
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
<?php endif; ?>
<?php if($createdBy or $modifiedBy): ?>
use yii\behaviors\BlameableBehavior;
<?php endif; ?>

/**
 * This is the model class for table "<?= $tableName ?>".
 *

<?php foreach ($tableSchema->columns as $column): ?>
 * @property <?= "{$column->phpType} \${$column->name}\n" ?>
<?php endforeach; ?>
<?php if (!empty($relations)): ?>
 *
<?php foreach ($relations as $name => $relation): ?>
 * @property <?= $relation[1] . ($relation[2] ? '[]' : '') . ' $' . lcfirst($name) . "\n" ?>
<?php endforeach; ?>
<?php endif; ?>
 */
class <?= $className ?> extends <?= '\\' . ltrim($generator->baseClass, '\\') . "\n" ?>
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '<?= $generator->generateTableName($tableName) ?>';
    }
	
<?php if($created or $modified or $createdBy or $modifiedBy): ?>
    /**
     * @inheritdoc
     */	
    public function behaviors()
    {
        return [
<?php if($created or $modified): ?>
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
    <?php if($created and $modified){ ?>
                    <?= '\\' . ltrim($generator->baseClass, '\\') ?>::EVENT_BEFORE_INSERT => ['created','modified'],
    <?php } else if($created){ ?>
                    <?= '\\' . ltrim($generator->baseClass, '\\') ?>::EVENT_BEFORE_INSERT => ['created'],
    <?php }; ?><?php if($modified): ?>
                    <?= '\\' . ltrim($generator->baseClass, '\\') ?>::EVENT_BEFORE_UPDATE => 'modified',
<?php endif; ?>
                ],
<?php if($created or $modified): ?>
                'value' => new Expression('NOW()'),
<?php endif; ?>
            ],
<?php endif; ?>
<?php if($createdBy or $modifiedBy): ?>
            'blameable' => [
                'class' => BlameableBehavior::className(),
                'attributes' => [
    <?php if($created and $modified){ ?>
                    <?= '\\' . ltrim($generator->baseClass, '\\') ?>::EVENT_BEFORE_INSERT => ['createdBy','modifiedBy'],
    <?php } else if($created){ ?>
                    <?= '\\' . ltrim($generator->baseClass, '\\') ?>::EVENT_BEFORE_INSERT => 'createdBy',
    <?php } ?><?php if($modifiedBy): ?>
                    <?= '\\' . ltrim($generator->baseClass, '\\') ?>::EVENT_BEFORE_UPDATE => 'modifiedBy',
<?php endif; ?>
                ],
            ],
<?php endif; ?>
        ];
    }
<?php endif; ?>
	
<?php if ($generator->db !== 'db'): ?>

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('<?= $generator->db ?>');
    }
<?php endif; ?>

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [<?= "\n            " . implode(",\n            ", $rules) . "\n        " ?>];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
<?php foreach ($labels as $name => $label): ?>
            <?= "'$name' => " . $generator->generateString($label) . ",\n" ?>
<?php endforeach; ?>
        ];
    }
<?php foreach ($relations as $name => $relation): ?>
	<?php
	$suffix_2=substr($name,0,2);
	$suffix_3=substr($name,0,3);
	if($suffix_3==='Tbl' or $suffix_3==='Ref'){
		$name=substr($name,3,255);
	}
	else if($suffix_2==='Tb'){
		$name=substr($name,2,255);
	}
	?>
    /**
     * @return \yii\db\ActiveQuery
     */
    public function get<?= $name ?>()
    {
        <?= $relation[0] . "\n" ?>
    }
<?php endforeach; ?>
}
