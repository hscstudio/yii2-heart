## Widget ##
---
Usage widget in view

### Navbar & Nav ###
---
```php
use hscstudio\heart\widgets\Nav;
use hscstudio\heart\widgets\NavBar;
NavBar::begin([
	'brandLabel' => '<i class="fa fa-rocket"></i> Yii2 - Heart',
	'brandUrl' => Yii::$app->homeUrl,
	'options' => [
		'class' => 'navbar-inverse navbar-fixed-top',
	],
	'innerContainerOptions'=>[
		'class' => 'container-fluid', // Please use container fluid
	]
]);

$menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
$menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
echo Nav::widget([
	'options' => ['class' => 'navbar-nav navbar-right'],
	'items' => $menuItems,
]);

NavBar::end();
```

### NavSide ###
---

Sidebar menu adminLTE like.. :) but not recommendate use this.. solution.. use kartik sidenav
```php
<?php
use hscstudio\heart\widgets\NavSide;
echo NavSide::widget([
	 'items' => [
		 [
			'icon'=>'fa fa-dashboard fa-fw',
			'label' => 'Dashboard',
			'url' => ['default/index'],
			'badge'=>'New',
			'badgeClass'=>'pull-right bg-green',
		 ],
		 [
			'icon'=>'fa fa fa-cogs fa-fw',
			'label' => 'Generator',
			'url' => ['/gii'],
		 ],
		 [
			'icon'=>'fa fa fa-warning fa-fw',
			'label' => 'Privileges',
			'url' => ['/privilege/assigment'],
		 ],
	 ],
 ]);
?>
```

### Breadcrumbs ###
---
```php
use hscstudio\heart\widgets\Breadcrumbs;
<?= Breadcrumbs::widget([
	'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
]) ?>
```

### Box ###
---
@see http://almsaeedstudio.com/AdminLTE/pages/widgets.html
Experimental
```
use hscstudio\heart\widgets\Box;
Box::begin([
	'type'=>'small', // ,small, solid, tiles
	'bgColor'=>'aqua', // , aqua, green, yellow, red, blue, purple, teal, maroon, navy, light-blue
	'options' => [],
	'headerOptions' => [
		'button' => ['collapse','remove'],
		'position' => 'right', //right, left
		'color' => '', //primary, info, warning, success, danger
		'class' => '',
	],
	'header' => '',
	'bodyOptions' => [],
	'icon' => '',
	'link' => '',
	'footerOptions' => [],
	'footer' => 'More info <i class="fa fa-arrow-circle-right"></i>',
]);
?>
	Your content of the box here
<?php
Box::end();
```

### FullCalendar ###
---
[FullCalendar](fullcalendar.md)
