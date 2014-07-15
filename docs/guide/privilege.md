Privilege
---------

Assigment
---------
Assigment menu used for grant or revoke role to/from user.

Role
----
Role menu used for manage role. You can create, delete or update role from this menu.
Adding and remove child of role can be doing there.

Permision
---------


Route
-----
Route is list of avaliable route of your application. It is automatic read application structure.
Click button '>>' to save it and button '<<' to delete.

Rule
----
see [Rules](http://www.yiiframework.com/doc-2.0/guide-authorization.html#using-rules).

You can then access Auth manager through the following URL:
```
http://localhost/path/to/index.php?r=privilege
```

See [Yii RBAC](http://www.yiiframework.com/doc-2.0/guide-authorization.html#role-based-access-control-rbac) for more detail.


Using Menu
----------

Menu manager used for build hierarchical menu. This is automatically look of user 
role and permision then return menus that he has access.

To use menu manager (optional). Execute yii migration
```
yii migrate --migrationPath=@hscstudio/heart/modules/admin/migration
```

```php
use hscstudio\heart\modules\admin\components\AccessHelper;
use hscstudio\heart\widgets\Nav;

echo Nav::widget([
    'items' => AccessHelper::getAssignedMenu(Yii::$app->user->id)
]);

```
Return of `hscstudio\heart\modules\admin\components\AccessHelper::getAssignedMenu()` has default format like:
```php
[
    [
        'label' => $menu['name'], 
        'url' => [$menu['route']],
        'items' => [
			[
				'label' => $menu['name'], 
				'url' => [$menu['route']]
            ],
            ....
        ]
    ],
    [
        'label' => $menu['name'], 
        'url' => [$menu['route']],
        'items' => [
			[
				'label' => $menu['name'], 
				'url' => [$menu['route']]
            ]
        ]
    ],
    ....
]
```
where `$menu` variable corresponden with a record of table `menu`. You can customize 
return format of `hscstudio\heart\modules\admin\components\AccessHelper::getAssignedMenu()` by provide a callback to this methode.
The callback must have format `function($menu){}`. E.g:
```php
$callback = function($menu){
    $data = eval($menu['data']);
    return [
        'label' => $menu['name'], 
        'url' => [$menu['route']],
        'options' => $data,
        'items' => $menu['children']
        ]
    ]
}

$items = AccessHelper::getAssignedMenu(Yii::$app->user->id, null, $callback);
```
Default result is get from `cache`. If you want to force regenerate, provide boolean `true` as forth parameter.


Second parameter of `hscstudio\heart\modules\admin\components\AccessHelper::getAssignedMenu()` used to get menu on it's own hierarchy.
E.g. Your menu structure:

* Side Menu
  * Menu 1
    * Menu 1.1
    * Menu 1.2
    * Menu 1.3
  * Menu 2
    * Menu 2.1
    * Menu 2.2
    * Menu 2.3
* Top Menu
  * Top Menu 1
    * Top Menu 1.1
    * Top Menu 1.2
    * Top Menu 1.3
  * Top Menu 2
  * Top Menu 3
  * Top Menu 4

You can get 'Side Menu' chldren by calling
```php
$items = AccessHelper::getAssignedMenu(Yii::$app->user->id, $sideMenuId);
```
It will result in
* Menu 1
  * Menu 1.1
  * Menu 1.2
  * Menu 1.3
* Menu 2
  * Menu 2.1
  * Menu 2.2
  * Menu 2.3
