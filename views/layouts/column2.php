<?php
use yii\helpers\Html;
use kartik\widgets\SideNav;
use hscstudio\heart\widgets\Breadcrumbs;
use kartik\widgets\AlertBlock;
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
			$menus = isset($this->params['sideMenu']) ? $this->params['sideMenu'] : [];
			$route = $controller->route;
			$items = [];
			foreach ($menus as $menu) {
				$active = strpos($route, trim($menu['url'][0], '/')) === 0 ? ' active' : '';
				if (strpos($route, trim($menu['url'][0], '/')) === 0) $this->title=$menu['label'];
				$icon = isset($menu['icon']) ? $menu['icon'] : 'link';
				$items[] = ['label'=>$menu['label'],'icon'=>$icon,'url'=>$menu['url'],'options'=>['class'=>$active]];
			}
			$this->params['sideMenu']=$items;
			echo SideNav::widget([
				//'type' => SideNav::TYPE_PRIMARY,
				//'heading' => 'Options',
				'items' => isset($this->params['sideMenu']) ? $this->params['sideMenu'] :  [
					[
						'url' => yii\helpers\Url::home(),
						'label' => 'Home',
						'icon' => 'home',
						'active' => true,						
					],
				]
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