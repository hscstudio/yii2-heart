<?php
namespace hscstudio\heart;

use Yii;
use yii\bootstrap\BootstrapAsset;
use yii\bootstrap\BootstrapPluginAsset;

/**
 * Description of Module
 * fa
 * @author Hafid Mukhlasin
 */
class Module extends \yii\base\Module implements \yii\base\BootstrapInterface
{
    /**
     *
     * @var array 
     */
	
	public $features=[
		'datecontrol'=>true,// use false for not use it
		'gridview'=>true,// use false for not use it
		'gii'=>true, // use false for not use it
		'privilege'=>true,// use false for not use it
		
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
		
		if(!isset($this->features['datecontrol'])) $this->features['datecontrol']=true;		
		if($this->features['datecontrol']!=false){		
			$app->setModules([
				'datecontrol' => [
					'class' => '\kartik\datecontrol\Module',			 
					// format settings for displaying each date attribute
					'displaySettings' => [
						\kartik\datecontrol\Module::FORMAT_DATE => 'd-M-Y',
						\kartik\datecontrol\Module::FORMAT_TIME => 'H:i:s',
						\kartik\datecontrol\Module::FORMAT_DATETIME => 'd-M-Y H:i:s',
					],			 
					// format settings for saving each date attribute
					'saveSettings' => [
						\kartik\datecontrol\Module::FORMAT_DATE => 'Y-m-d', // U if saves as unix timestamp
						\kartik\datecontrol\Module::FORMAT_TIME => 'H:i:s',
						\kartik\datecontrol\Module::FORMAT_DATETIME => 'Y-m-d H:i:s',
					],					
				]
			]);
		}
		
		if(!isset($this->features['gridview'])) $this->features['gridview']=true;
		if($this->features['gridview']!=false){		
			$app->setModules([
				'gridview' => [
					'class' => '\kartik\grid\Module',
				]
			]);
		}
		
		if(!isset($this->features['gii'])) $this->features['gii']=true;
		if($this->features['gii']!=false){	
			$app->setModules([
				'gii' => [
					'class' => 'yii\gii\Module',
					'allowedIPs' => ['127.0.0.1', '::1', '192.168.0.*', '192.168.178.20'],  
					'generators' => [
						'crud' => [
							'class' => 'hscstudio\heart\modules\gii\crud\Generator',
							//'class' => 'yii\gii\generators\crud\Generator', //class generator
							'templates' => [
								'my' => '@hscstudio/heart/modules/gii/crud/default',
							]
						],
						/*'model' => [
							'class' => 'hscstudio\heart\modules\gii\model\Generator',
							'templates' => [
								'my' => '@hscstudio/heart/modules/gii/model/default',
							]
						],
						'module' => [
							'class' => 'hscstudio\heart\modules\gii\module\Generator',
							'templates' => [
								'my' => '@hscstudio/heart/modules/gii/module/default',
							]
						],*/
					],
				]
			]);
		}
		
		if(!isset($this->features['privilege'])) $this->features['privilege']=true;
		if($this->features['privilege']!=false){		
			$authManager = yii\helpers\ArrayHelper::remove($this->features['privilege'], 'authManager', [
					'class' => 'yii\rbac\DbManager',
			]);
			$allowActions = yii\helpers\ArrayHelper::remove($this->features['privilege'], 'allowActions', [
					'debug/*',
					'site/*',
					'gii/*',
					'privilege/*', 
					'gridview/*',
					// add or remove allowed actions to this list
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
		
		$pathMap['@app/views/layouts'] = '@hscstudio/heart/views/layouts';
		
		if (!empty($pathMap)) {
			$view->theme = Yii::createObject([
					'class' => 'yii\base\Theme',
					'pathMap' => $pathMap
			]);
		}
		
		$assets = $view->assetManager->publish('@hscstudio/heart/assets/heart');
		$view->registerCssFile($assets[1].'/css/heart.css', [
			'depends' => [BootstrapAsset::className()],
		],'css-heart');
		
		$view->registerCssFile($assets[1].'/css/metroui.css', [
			'depends' => [BootstrapAsset::className()],
		], 'css-metroui');
		
		$view->registerJsFile($assets[1].'/js/heart.js', [
			'depends' => [BootstrapPluginAsset::className()]
		]);
		$css = '
		.overlay, .loading-img {
			  position: fixed;
			  top: 0;
			  left: 0;
			  width: 100%;
			  height: 100%;
		}
		
		.overlay {
		  z-index: 1010;
		  background: rgba(255, 255, 255, 0.7);
		}
		
		.overlay.dark {
		  background: rgba(0, 0, 0, 0.5);
		}
		
		.loading-img {
		  z-index: 1020;
		  background: transparent url("'.$assets[1].'/img/ajax-loader1.gif") 50% 20% no-repeat;
		}
		
		.bootstrap-switch {
			min-width:125px !important;
		}
		';
		$view->registerCss($css);
		
		$view->registerJsFile($assets[1].'/js/bootstrap-growl.min.js', [
			'depends' => [BootstrapPluginAsset::className()]
		]);
		
		\yii\base\Event::on('yii\web\Controller','beforeAction',function($event){
			if($event->sender->uniqueId=='site'){
				$event->sender->layout = 'column1';
			}
		});
		
		
    }
}
