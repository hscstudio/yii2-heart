<?php
/**
 * This is the template for generating a controller class within a module.
 */

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\module\Generator */

echo "<?php\n";
?>

namespace <?= $generator->getControllerNamespace() ?>;

use yii\web\Controller;

class DefaultController extends Controller
{
	<?php if($_POST['Generator']['layout']=='column2'){ ?>
	public $layout = '@hscstudio/heart/views/layouts/column2';
	<?php } else { ?>
	public $layout = '@hscstudio/heart/views/layouts/column1';
	<?php } ?> 
	
	public function actionIndex()
    {
        return $this->render('index');
    }
}
