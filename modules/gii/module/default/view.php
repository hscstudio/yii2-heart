<?php
/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\module\Generator */
?>
<?= "<?php " ?>
<?php if(!empty($_POST['Generator']['moduleID'])){ ?>
$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu']=$menus;
<?php } ?>
?>
<div class="<?= $generator->moduleID . '-default-index' ?>">
    <h1><?= "<?= " ?>$this->context->action->uniqueId ?></h1>
    <p>
        This is the view content for action "<?= "<?= " ?>$this->context->action->id ?>".
        The action belongs to the controller "<?= "<?= " ?>get_class($this->context) ?>"
        in the "<?= "<?= " ?>$this->context->module->id ?>" module.
    </p>
	<p>
        You may customize side bar menu by editing folowing file:<br>
		<?= "<?php " ?>$path = explode('views',__FILE__);  ?>
        <code><?= "<?= " ?>$path[0]; ?>Module.php</code>
    </p>
    <p>
        You may customize this page by editing the following file:<br>
        <code><?= "<?= " ?>__FILE__ ?></code>
    </p>
</div>