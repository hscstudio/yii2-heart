yii2-heart
=========

Yii2-Heart is a Extension For Yii Framework 2.0. It is set of useful extensions or library that add functionality. The feature are

  - Bootstrap Widget for AdminLTE
  - etc.

Yii2-Heart is maintain by Hafid Mukhlasin http://www.hafidmukhlasin.com. Our motto:

> Create professional web Application
> in minutes, Are You Ready?

This text you see here is *actually* written in Markdown! To get a feel for Markdown's syntax, type some text into the left window and watch the results in the right.  

Version
----

1.0 (July 2014)

Technology
-----------

Dillinger uses a number of open source projects to work properly:

* [AdminLTE] - awesome free web template https://github.com/almasaeed2010/AdminLTE
* etc

Installation
--------------
Before You use this extension, You should know how to integrate Yii2 and AdmiLTE. You can read http://www.yiiframework.com/wiki/729/tutorial-about-how-to-integrate-yii2-with-fantastic-theme-adminlte/ 
Prefered way install with composer, add this setting to Your composer.json

```php
"hsctudio/yii2-heart": "*"
```
Then update your project, for example via command prompt windows

```php
c:\xampp\php\php.exe c:\xampp\php\composer.phar update --prefer-dist
```

Using
--------------

### Widgets
* NavLTE

```php
use hscstudio\heart\widgets\NavLTE;
echo NavLTE::widget([
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
]);
```
* BreadcrumbsLTE

```php
use hscstudio\heart\widgets\BreadcrumbsLTE;
echo BreadcrumbsLTE::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
]);
```

License
----

MIT


**Th3Pr0f3550r!**
