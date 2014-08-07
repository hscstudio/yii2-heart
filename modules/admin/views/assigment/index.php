<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var mdm\admin\models\AssigmentSearch $searchModel
 */
$this->title = 'Assigments';
$this->params['breadcrumbs'][] = $this->title;

$controller = $this->context;
$menus = $controller->module->menus;
$route = $controller->route;
$items = [];
foreach ($menus as $menu) {
	$active = strpos($route, trim($menu['url'][0], '/')) === 0 ? ' active' : '';
	if (strpos($route, trim($menu['url'][0], '/')) === 0) $this->title=$menu['label'];
	$items[] = ['label'=>$menu['label'],'icon'=>'link','url'=>$menu['url'],'options'=>['class'=>$active]];
}
$this->params['sideMenu']=$items;
?>
<?php $this->beginContent('@hscstudio/heart/views/layouts/column2module.php'); ?>
<div class="assigment-index">


	<?php
    Pjax::begin([
        'enablePushState'=>false,
    ]);
	echo GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'columns' => [
			['class' => 'yii\grid\SerialColumn'],
			[
				'class' => 'yii\grid\DataColumn',
				'attribute' => $usernameField,
			],
			[
				'class' => 'yii\grid\ActionColumn',
                'template'=>'{view}'
			],
		],
	]);
    Pjax::end();
	?>
</div>
<?php $this->endContent();
