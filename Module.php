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
		'fontawesome'=>true,
		'gii'=>[],
		'privilege'=>[],
		'user'=>[]	
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
		
		if(isset($this->features['gii'])){		
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
		
		if(isset($this->features['user'])){		
			$app->setModule('user', 
				yii\helpers\ArrayHelper::merge([ // bisa juga pake yii\helpers\ArrayHelper::merge
					'class' => 'dektrium\user\Module',
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
		
		if(isset($this->features['privilege'])){	
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
				'class' => 'mdm\admin\Module',
					], $this->features['privilege']));
	 
			$app->attachBehavior('access', [
				'class' => 'mdm\admin\components\AccessControl',
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
		
		
		if(isset($this->features['fontawesome']) and $this->features['fontawesome']){
			$assets = $view->assetManager->publish('@hscstudio/heart/assets/fontawesome');
			$view->registerCssFile($assets[1].'/css/font-awesome.min.css');
		}
    }
}