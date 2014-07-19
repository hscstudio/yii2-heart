<?php
use yii\helpers\Html;
use hscstudio\heart\widgets\NavSide;
use hscstudio\heart\widgets\Breadcrumbs;
/**
 * @var \yii\web\View $this
 * @var string $content
 */
$controller = $this->context;
$menus = $controller->module->menus;
$route = $controller->route;
?>

<div class="wrapper row-offcanvas row-offcanvas-left">
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="left-side sidebar-offcanvas">                
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
        <?php
		$items = [];
		foreach ($menus as $menu) {
			$label = Html::tag('i', '', ['class' => 'glyphicon glyphicon-chevron-right pull-right']) .
				Html::tag('span', Html::encode($menu['label']), []);
			$active = strpos($route, trim($menu['url'][0], '/')) === 0 ? ' active' : '';
			if (strpos($route, trim($menu['url'][0], '/')) === 0) $this->title=$menu['label'];
			/*
			echo Html::a($label, $menu['url'], [
				'class' => 'list-group-item' . $active,
			]);*/
			$items[] = ['label'=>$menu['label'],'icon'=>'fa fa-arrow-circle-right','url'=>$menu['url'],'options'=>['class'=>$active]];
		}
		echo NavSide::widget([
			 'items' => $items,
			 ]);
		?>        
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Right side column. Contains the navbar and content of the page -->
    <aside class="right-side">   
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1><?= Html::encode($this->title) ?></h1>
        </section>
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <!-- Main content -->
        <section class="content">
            <?= $content ?>
        </section><!-- /.content -->
    </aside><!-- /.right-side -->
</div><!-- ./wrapper -->