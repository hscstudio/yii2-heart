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
    public $layout = '@hscstudio/heart/views/layouts/column2';
	public function actionIndex()
    {
        return $this->render('index');
    }
}
