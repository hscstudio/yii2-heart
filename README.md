Yii2-heart
========================

Yii2-heart is extension for Yii Framework version 2.0. It is set of extension (widget, modules, wrapper, etc) to easiest developer in application development.

Warning : This extension under development 
Dosc:
-----
- [change log](CHANGELOG.md).
- [Basic Usage](docs/guide/basic-usage.md).
- [Using Menu](docs/guide/using-menu.md).

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require hscstudio/yii2-heart "*"
```

or add

```
"hscstudio/yii2-heart": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply modify your application configuration as follows:

```php
return [
	'bootstrap' => [
		'privilege',
		'hscstudio\heart\Init'
		...
	],
	'modules' => [
		'privilege' => [
			'class' => 'hscstudio\heart\modules\admin\Module',
			'allowActions' => [
				'debug/*',
                		'privilege/*'
				'admin/*', // add or remove allowed actions to this list
			]
		]
		...
	],
	...
	'components' => [
		....
		'authManager' => [
			'class' => 'yii\rbac\DbManager', // or use 'yii\rbac\PhpManager'
		]
	],
];
```

See [Yii RBAC](http://www.yiiframework.com/doc-2.0/guide-authorization.html#role-based-access-control-rbac) for more detail.
You can then access Auth manager through the following URL:

```
http://localhost/path/to/index.php?r=privilege
```

To use menu manager (optional). Execute yii migration
```
yii migrate --migrationPath=@hscstudio/heart/modules/admin/migration
```

[screenshots]
