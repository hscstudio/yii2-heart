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
    public $allowActions = [];
	
	public $features=[
		'fontawesome'=>true,
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
		
		if(isset($this->features['user'])){		
			$app->setModules([
				'user' => [
					'class' => 'dektrium\user\Module',
					'components' => isset($this->features['user']['components'])?$this->features['user']['components']:[],        			
					'confirmable' => isset($this->features['user']['confirmable'])?$this->features['user']['confirmable']:false,
					'confirmWithin' =>  isset($this->features['user']['confirmWithin'])?$this->features['user']['confirmWithin']:86400, 
					'allowUnconfirmedLogin' => isset($this->features['user']['allowUnconfirmedLogin'])?$this->features['user']['allowUnconfirmedLogin']:true,
					'rememberFor' => isset($this->features['user']['rememberFor'])?$this->features['user']['rememberFor']:1209600,
					'recoverWithin' => isset($this->features['user']['recoverWithin'])?$this->features['user']['recoverWithin']:21600,
					'admins' => isset($this->features['user']['admins'])?$this->features['user']['admins']:['admin'],
					'cost' => isset($this->features['user']['cost'])?$this->features['user']['cost']:13,
				],
			]);
			
			$pathMap['@dektrium/user/views'] = '@hscstudio/heart/modules/user/views';
		}
		
		if(isset($this->features['privilege'])){	
			$app->set('authManager', [
					'class' => isset($this->features['privilege']['authManagerClass'])?$this->features['privilege']['authManagerClass']:'yii\rbac\DbManager',
					]
				);
			
			$app->setModules([
				'privilege'=>[
						'class' => 'mdm\admin\Module',
					]
				]);
			
			$this->allowActions = isset($this->features['privilege']['allowActions'])?$this->features['privilege']['allowActions']:['debug/*',
                'site/*',
                'gii/*',
                'privilege/*',
                'user/*', // add or remove allowed actions to this list
			];
			\mdm\admin\Module::bootstrap($app);
			
			$pathMap['@mdm/admin/views'] = '@hscstudio/heart/modules/admin/views';
		}
		
		$view->theme->pathMap = $pathMap;		
		
		$assets = $view->assetManager->publish('@hscstudio/heart/assets/heart');
		$view->registerCssFile($assets[1].'/css/heart.css', ['yii\bootstrap\BootstrapAsset']);
		$view->registerJsFile($assets[1].'/js/heart.js', ['yii\web\JqueryAsset']);
		
		if(isset($this->features['fontawesome']) and $this->features['fontawesome']){
			$assets = $view->assetManager->publish('@hscstudio/heart/assets/fontawesome');
			$view->registerCssFile($assets[1].'/css/font-awesome.min.css');
		}
    }
}