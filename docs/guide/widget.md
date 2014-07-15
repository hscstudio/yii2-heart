Widget
---------
Usage widget in view

Before You use widget, You should use Init
```php
use hscstudio\heart\Init;
```

- Navbar & Nav
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