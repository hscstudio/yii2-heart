Yii2-heart
========================

Yii2-heart is extension for Yii Framework version 2.0. It is set of extension (widget, modules, wrapper, etc) to easiest developer in application development.

Warning : This extension under development or PUBLIC PREVIEW :)


Roadmap
------------
Done
- Wrap Yii2-user (https://github.com/dektrium/yii2-user)
- Wrap Yii2-admin (https://github.com/mdmsoft/yii2-admin)
- Wrap Arshaw Calendar 
- Wrap PHPExcel (http://www.yiiframework.com/forum/index.php/topic/52199-how-to-load-phpexcel-in-yii-20-project/)
- Database Migration

```
$objPHPExcel = new \PHPExcel();
```

Progress..
- Wrap adminLTE -> create widget content
  (done Navbar, NavSide, Nav, Dropdown, Breadcrumbs, Box)

Waiting
- Wrap TCPDF or FPDF
- Wrap TinyButStrong
- Wrap Highchart
- Create Widget Export Excel, Word, Pdf
- Create Widget Import Excel
- Create Gii Improved base on this extension

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
				// default on all features:
				// fontawesome, datecontrol (kartik), gridview (kartik), gii, privilege (yii2-admin), user (yii2-user)
			]
		],
		...
	],
	...
];
```

And then please read  Guide 
- [Index Guide](docs/guide/index.md)
