## Layout ##
in @app\views\layouts\main.php
please use class css container-fluid not container

for make 2 column layouts, You should create a file column2.php, this use adminLTE style.
```php
<?php
use yii\helpers\Html;
use hscstudio\heart\widgets\Breadcrumbs;
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
            <h1><?= Html::encode($this->title) ?></h1>
        </section>
        <?= Breadcrumbs::widget([
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