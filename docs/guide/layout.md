## Layout ##

Yii2-heart have automatic handle Your layout and support 2 column, column1 and column2.
You can check this layouts code in hscstudio\heart\views\layouts

If You generate model with 2 column, You can add sidebar menu
```php
/* menu should return this
[
	['icon'=>'fa fa-fw fa-dashboard','label' => 'Employee', 'url' => ['employee/index'], path=>'employee'],
];
```

If You not use module, You should write menu in Your params config
```php
$this->params['sideMenu']['heart-global']=[
	['icon'=>'fa fa-fw fa-dashboard','label' => 'Employee', 'url' => ['employee/index'], path=>'employee'],
]
```

But if You use module You should edit file Module.php in Your module