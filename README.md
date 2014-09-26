Yii2-heart
========================

Yii2-heart is extension for Yii Framework version 2.0. It is set of extension (widget, modules, wrapper, etc) to easiest developer in application development.

Warning : This extension under development or PUBLIC PREVIEW :)


Feature
------------
- RBAC ready via Yii2-admin (https://github.com/mdmsoft/yii2-admin)
- Calendar Event via Arshaw Calendar 
- Various UI Widget via Kartik-v Extension
- Export to various file format via: PHPExcel, TCPDF, TinyButStrong
- Import Excel ready via PHPExcel
- Charting ready via Highchart

Progress..
- Update manual & guide
- Database Migration 
- Gii Improved base on above extension

Waiting
- any idea?


Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require hscstudio/yii2-heart "dev-master"
```

or add

```
"hscstudio/yii2-heart": "dev-master"
```

to the require section of your `composer.json` file.

Database migration

```
yii migrate --migrationPath=@hscstudio\heart\migrations
```


Usage
-----

Once the extension is installed, simply modify your application configuration as follows:

```php
return [
	'bootstrap' => [		
		'heart',
		...
	],
	'modules' => [
		'heart' => [
			'class' => 'hscstudio\heart\Module',
			'features'=>[
				// datecontrol (kartik), gridview (kartik), gii, 
				'datecontrol'=>true,// use false for not use it
				'gridview'=>true,// use false for not use it
				'gii'=>true, // use false for not use it
				// privilege (yii2-admin)
			],
		],
		...
	],
	...
];
```

And then please read  Guide 
- [Index Guide](docs/guide/index.md)
