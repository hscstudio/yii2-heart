<?php
namespace hscstudio\heart;

use Yii;

/**
 * Description of Module
 *
 * @author Hafid Mukhlasin
 */
class Module extends \yii\base\Module implements \yii\base\BootstrapInterface
{
    /**
     *
     * @var array 
     */
	
	public $features=[
		'fontawesome'=>true, // use false for not use it
		'datecontrol'=>true,// use false for not use it
		'gridview'=>true,// use false for not use it
		'gii'=>true, // use false for not use it
		'privilege'=>[],// use false for not use it
		'user'=>[]	// use false for not use it
	];
	
    public function init()
    {
        parent::init();
    }

    /**
     * 
     * @param \yii\web\Application $app
     */
    public function bootstrap($app)
    {	
		$app->set('view', [
				'class' => 'yii\web\View',
				'theme' => [
					'pathMap' => [
						''=>'',
					],					
				],
			]
		);
		
		$view = $app->getView();
		$pathMap=[];
			
		if(@$this->features['datecontrol']!=false){		
			$app->setModules([
				'datecontrol' => [
					'class' => '\kartik\datecontrol\Module',			 
					// format settings for displaying each date attribute
					'displaySettings' => [
						\kartik\datecontrol\Module::FORMAT_DATE => 'd-M-Y',
						\kartik\datecontrol\Module::FORMAT_TIME => 'H:i:s A',
						\kartik\datecontrol\Module::FORMAT_DATETIME => 'd-M-Y H:i:s A',
					],			 
					// format settings for saving each date attribute
					'saveSettings' => [
						\kartik\datecontrol\Module::FORMAT_DATE => 'Y-m-d', // U if saves as unix timestamp
						\kartik\datecontrol\Module::FORMAT_TIME => 'H:i:s',
						\kartik\datecontrol\Module::FORMAT_DATETIME => 'Y-m-d H:i:s',
					],			 
					// automatically use kartik\widgets for each of the above formats
					'autoWidget' => true,			 
					// default settings for each widget from kartik\widgets used when autoWidget is true
					'autoWidgetSettings' => [
						\kartik\datecontrol\Module::FORMAT_DATE => [
							'type'=>2, 'pluginOptions'=>['autoClose'=>true]
						],
						\kartik\datecontrol\Module::FORMAT_DATETIME => [], // setup if needed
						\kartik\datecontrol\Module::FORMAT_TIME => [], // setup if needed
					],
				]
			]);
		}
		
		if(@$this->features['gridview']!=false){		
			$app->setModules([
				'gridview' => [
					'class' => '\kartik\grid\Module',
				]
			]);
		}
		
		if(@$this->features['gii']!=false){		
			$app->setModules([
				'gii' => [
					'class' => 'yii\gii\Module',
					'allowedIPs' => ['127.0.0.1', '::1', '192.168.0.*', '192.168.178.20'],  
					'generators' => [
						'crud' => [
							'class' => 'hscstudio\heart\modules\gii\crud\Generator',
							'templates' => [
								'my' => '@hscstudio/heart/modules/gii/crud/default',
							]
						],
						'model' => [
							'class' => 'hscstudio\heart\modules\gii\model\Generator',
							'templates' => [
								'my' => '@hscstudio/heart/modules/gii/model/default',
							]
						],
					],
				]
			]);
		}
		
		if(@$this->features['user']!=false){			
			$app->setModule('user', 
				yii\helpers\ArrayHelper::merge([ // bisa juga pake yii\helpers\ArrayHelper::merge
					'class' => '\dektrium\user\Module',
					'confirmable' => false,
					'confirmWithin' => 86400,
					'allowUnconfirmedLogin' => true,
					'rememberFor' => 1209600,
					'recoverWithin' => 21600,
					'admins' => ['admin'],
					'cost' => 13,
				], $this->features['user'])
			);
			
			$pathMap['@dektrium/user/views'] = '@hscstudio/heart/modules/user/views';
		}
		
		if(@$this->features['privilege']!=false){		
			$authManager = yii\helpers\ArrayHelper::remove($this->features['privilege'], 'authManager', [
					'class' => 'yii\rbac\DbManager',
			]);
			$allowActions = yii\helpers\ArrayHelper::remove($this->features['privilege'], 'allowActions', [
					'debug/*',
					'site/*',
					'gii/*',
					'privilege/*',
					'user/*', // add or remove allowed actions to this list
			]);
			$app->set('authManager', $authManager);
	 
			$app->setModule('privilege', array_merge([
				'class' => '\mdm\admin\Module',
					], $this->features['privilege']));
	 
			$app->attachBehavior('access', [
				'class' => '\mdm\admin\components\AccessControl',
				'allowActions' => $allowActions,
			]);
			
			//$app->getModule('privilege')->bootstrap($app);
			
			$pathMap['@mdm/admin/views'] = '@hscstudio/heart/modules/admin/views';
		}
		
		if (!empty($pathMap)) {
			$view->theme = Yii::createObject([
					'class' => 'yii\base\Theme',
					'pathMap' => $pathMap
			]);
		}
		
		$assets = $view->assetManager->publish('@hscstudio/heart/assets/heart');
		$view->registerCssFile($assets[1].'/css/heart.css', ['yii\bootstrap\BootstrapAsset']);
		$view->registerJsFile($assets[1].'/js/heart.js', ['yii\web\JqueryAsset']);
		
		
		if(@$this->features['fontawesome']!=false){
			$assets = $view->assetManager->publish('@hscstudio/heart/assets/fontawesome');
			$view->registerCssFile($assets[1].'/css/font-awesome.min.css');
		}
    }
}