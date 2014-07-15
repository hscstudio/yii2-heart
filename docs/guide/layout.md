Layout
---------
in @app\views\layouts\main.php
```php
use hscstudio\heart\Init;

```

Then please use container-fluid not container

for make 2 column layouts, You should create a file column2.php, this use adminLTE style.
```php
<?php
use yii\helpers\Html;
?>
<?php $this->beginContent('@app/views/layouts/main.php'); ?>
<div class="wrapper row-offcanvas row-offcanvas-left">
    <aside class="left-side sidebar-offcanvas">                
        <section class="sidebar">
			<!-- Add menu or navigation here -->
        </section>
    </aside>

    <aside class="right-side">   
        <section class="content-header">
            <h1><?= \yii\helpers\Html::encode($this->title) ?></h1>
        </section>
        <?= \hscstudio\heart\widgets\Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <!-- Main content -->
        <section class="content">
            <?= $content ?>
        </section>
    </aside>
</div>
<?php $this->endContent();

```

And then in controller that use variabel layout and set it to be column2
```php
public $layout="column2";
```