<?php
use yii\helpers\Html;
use kartik\widgets\SideNav;
use hscstudio\heart\widgets\Breadcrumbs;
use kartik\widgets\AlertBlock;
use kartik\icons\Icon;
 
// Set default icon fontawesome
Icon::map($this, Icon::FA);
/**
 * @var \yii\web\View $this
 * @var string $content
 */
?>
<?php $this->beginContent('@app/views/layouts/main.php'); ?>
<div class="wrapper row-offcanvas row-offcanvas-left">
    <aside class="left-side sidebar-offcanvas">             
        <section class="sidebar">
			<?php
			$controller = $this->context;
			$menus = [];
			if(isset($this->params['sideMenu'][$controller->module->uniqueId])){
				$menus = $this->params['sideMenu'][$controller->module->uniqueId];
			}
			else if(isset($this->params['sideMenu']['heart-global'])){
				$menus = $this->params['sideMenu']['heart-global'];
			}
			/* menu should return this
			[
				['icon'=>'fa fa-fw fa-dashboard','label' => 'Employee', 'url' => ['employee/index'], path=>'employee'],
			];
			
			If You not use module, You should write menu in Your params config
			$this->params['sideMenu']['heart-global']=[
				['icon'=>'fa fa-fw fa-dashboard','label' => 'Employee', 'url' => ['employee/index'], path=>'employee'],
			]
			*/
			$route = $controller->route;
			$items = [];
			foreach ($menus as $menu) {
				$active = strpos($route, trim($menu['url'][0], '/')) === 0 ? ' active' : '';
				if (strpos($route,@$menu['path'])!== false) $active = ' active';
				if (strpos($route, trim($menu['url'][0], '/')) === 0) $this->title=$menu['label'];
				$icon = isset($menu['icon']) ? $menu['icon'] : 'glyphicon glyphicon-link';
				$menus2 = isset($menu['items']) ? $menu['items'] : [];
				$items2 = [];
				foreach ($menus2 as $menu2) {
					$active2 = strpos($route, trim($menu2['url'][0], '/')) === 0 ? ' active' : '';
					if (strpos($route,@$menu2['path'])!== false) $active2 = ' active';
					if($active2==' active') $active = ' active';
					if (strpos($route, trim($menu2['url'][0], '/')) === 0) $this->title=$menu2['label'];
					$icon2 = isset($menu2['icon']) ? $menu2['icon'] : 'glyphicon glyphicon-link';
					$menus3 = isset($menu2['items']) ? $menu2['items'] : [];
					foreach ($menus3 as $menu3) {
						$active3 = strpos($route, trim($menu3['url'][0], '/')) === 0 ? ' active' : '';
						if (strpos($route,@$menu3['path'])!== false) $active3 = ' active';
						if($active3==' active') $active2 = ' active';
						if (strpos($route, trim($menu3['url'][0], '/')) === 0) $this->title=$menu3['label'];
						$icon3 = isset($menu3['icon']) ? $menu3['icon'] : 'glyphicon glyphicon-link';
					}
					$items2[] = ['label'=>$menu2['label'],'icon'=>$icon2,'url'=>$menu2['url'],'options'=>['class'=>$active2],'items'=>$menus3];
				}
				
				$items[] = ['label'=>$menu['label'],'icon'=>$icon,'url'=>$menu['url'],'options'=>['class'=>$active],'items'=>$items2];
			}
			echo SideNav::widget([
				//'type' => SideNav::TYPE_PRIMARY,
				//'heading' => 'Options',
				'items' => !empty($items) ? $items :  [
					[
						'url' => yii\helpers\Url::home(),
						'label' => 'Home',
						'icon' => 'glyphicon glyphicon-home',
						'active' => true,						
					],
				],
				'iconPrefix' => ''
			]);
			?>
        </section>
    </aside>

    <aside class="right-side">   
        <section class="content-header">
            <h1><?= Html::encode($this->title) ?></h1>
        </section>
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <section class="content">
			<?php 
			echo AlertBlock::widget([
				'useSessionFlash' => true,
				'type' => AlertBlock::TYPE_ALERT
			]); 
			?>
            <?= $content ?>
        </section>
    </aside>
</div>
<?php $this->endContent();