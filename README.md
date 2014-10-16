Yii2-heart
========================

Yii2-heart is extension for Yii Framework version 2.0. It is set of extension (widget, modules, wrapper, etc) to easiest developer in application development.

Roadmap
------------
Done
- Wrap Yii2-admin
- Wrap Arshaw Calendar 
- Use Kartik-v Extension
- Wrap PHPExcel (http://www.yiiframework.com/forum/index.php/topic/52199-how-to-load-phpexcel-in-yii-20-project/)
```
$objPHPExcel = new \PHPExcel();
```
- Wrap TCPDF (no wrap only use)
- Wrap TinyButStrong
- Database Migration
- Import Excel ready
- Gii Improved base on above extension
- Highchart ready

Progress..
- Update manual
- Bugfix

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
				// privilege (yii2-admin) please read guide
			],
		],
		...
	],
	...
];
```

And then please read  Guide 
- [Index Guide](docs/guide/index.md)
