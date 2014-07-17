Yii2-heart
========================

Yii2-heart is extension for Yii Framework version 2.0. It is set of extension (widget, modules, wrapper, etc) to easiest developer in application development.

Warning : This extension under development or PUBLIC PREVIEW :)


Roadmap
------------
Done
- Integrate Yii2-admin
- Wrap Arshaw Calendar

Progress..
- Wrap adminLTE -> create widget content
  (done Navbar, NavSide, Nav, Dropdown, Breadcrumbs)

Waiting
- Wrap PHPExcel
- Wrap TCPDF or FPDF
- Wrap TinyButStrong
- Create Widget Export Excel, Word, Pdf
- Create Widget Import Excel
- Create Gii Improved base on this extension

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


Usage
-----

Once the extension is installed, simply modify your application configuration as follows:

```php
return [
	'bootstrap' => [		
		'heart',
		'privilege',
		...
	],
	'modules' => [
		'heart' => [
			'class' => 'hscstudio\heart\Module',
		],
		'privilege' => [
			'class' => 'hscstudio\heart\modules\admin\Module',
			'allowActions' => [
				'debug/*',
                'privilege/*', // add or remove allowed actions to this list
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
