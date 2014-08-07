<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */


use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var dektrium\user\models\User $model
 */

$this->title = Yii::t('user', 'Update user account');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['sideMenu'] = [
	['icon'=>'user', 'label' => Yii::t('user', 'Manage Users'), 'url' => ['/user/admin/index']]
];
?>
<?php $this->beginContent('@hscstudio/heart/views/layouts/column2module.php'); ?>
<h1><i class="glyphicon glyphicon-user"></i> <?= Html::encode($model->username) ?>
    <?php if (!$model->getIsConfirmed()): ?>
        <?= Html::a(Yii::t('user', 'Confirm'), ['confirm', 'id' => $model->id], ['class' => 'btn btn-success btn-xs', 'data-method' => 'post']) ?>
    <?php endif; ?>
    <?php if (!is_null($model->recovery_token)): ?>
        <?= Html::a(Yii::t('user', 'Delete recovery tokens'), ['delete-tokens', 'id' => $model->id], ['class' => 'btn btn-warning btn-xs', 'data-method' => 'post']) ?>
    <?php endif; ?>
    <?php if ($model->getIsBlocked()): ?>
        <?= Html::a(Yii::t('user', 'Unblock'), ['block', 'id' => $model->id], ['class' => 'btn btn-success btn-xs', 'data-method' => 'post', 'data-confirm' => Yii::t('user', 'Are you sure to block this user?')]) ?>
    <?php else: ?>
        <?= Html::a(Yii::t('user', 'Block'), ['block', 'id' => $model->id], ['class' => 'btn btn-danger btn-xs', 'data-method' => 'post', 'data-confirm' => Yii::t('user', 'Are you sure to block this user?')]) ?>
    <?php endif; ?>
</h1>

<?php if (Yii::$app->getSession()->hasFlash('admin_user')): ?>
    <div class="alert alert-success">
        <p><?= Yii::$app->getSession()->getFlash('admin_user') ?></p>
    </div>
<?php endif; ?>

<div class="panel panel-info">
    <div class="panel-heading"><?= Yii::t('user', 'Information') ?></div>
    <div class="panel-body">
        
        <?php echo date('d-m-Y H:i:s',$model->created_at).' from '.is_null($model->registered_from) ? 'N/D' : long2ip($model->registered_from) ; ?>
        <br/>
        <?php if (Yii::$app->getModule('user')->confirmable && $model->getIsConfirmed()): ?>
            <?php echo date('d-m-Y H:i:s',$model->created_at) ?>
            <br/>
        <?php endif; ?>
        <?php if (!is_null($model->logged_in_at)): ?>
            <?php echo date('d-m-Y H:i:s',$model->logged_in_at).' from '.long2ip($model->logged_in_from) ; ?>
        <?php endif;?>
        <?php if ($model->getIsBlocked()): ?>
            <?php echo date('d-m-Y H:i:s',$model->blocked_at); ?>
        <?php endif;?>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <?= Html::encode($this->title) ?>
    </div>
    <div class="panel-body">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'username')->textInput(['maxlength' => 25]) ?>

        <?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>

        <?= $form->field($model, 'password')->passwordInput() ?>

        <?= $form->field($model, 'role')->textInput(['maxlength' => 255]) ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('user', 'Save'), ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
<?php $this->endContent();