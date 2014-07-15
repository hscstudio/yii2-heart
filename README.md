Yii2-heart
========================

Yii2-heart is extension for Yii Framework version 2.0. It is set of extension (widget, modules, wrapper, etc) to easiest developer in application development.

Warning : This extension under development 

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

And then please read  Guide 
- [Index Guide](docs/guide/index.md)
