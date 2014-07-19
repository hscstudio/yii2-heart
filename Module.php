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
			
		$app->setModules([
			'privilege'=>[
					'class' => 'mdm\admin\Module',
				],
			'user' => [
				'class' => 'dektrium\user\Module',
				'components' => [
					'manager' => [
						// Active record classes
						//'userClass'    => 'dektrium\user\models\User',
						//'profileClass' => 'dektrium\user\models\Profile',
						//'accountClass' => 'dektrium\user\models\Account',
						// Model that is used on resending confirmation messages
						//'resendFormClass' => 'dektrium\user\models\ResendForm',
						// Model that is used on logging in
						//'loginFormClass' => 'dektrium\user\models\LoginForm',
						// Model that is used on password recovery
						//'passwordRecoveryFormClass' => 'dektrium\user\models\RecoveryForm',
						// Model that is used on requesting password recovery
						//'passwordRecoveryRequestFormClass' => 'dektrium\user\models\RecoveryRequestForm',
					],
				],        			
				'confirmable' => false,
				'confirmWithin' =>  86400, 
				'allowUnconfirmedLogin' => true,
				'rememberFor' => 1209600,
				'recoverWithin' => 21600,
				'admins' => ['admin'],
				'cost' => 13,
			],
		]);
		
		\mdm\admin\Module::bootstrap($app);	
		
		$app->set('view', [
				'class' => 'yii\web\View',
				'theme' => [
					'pathMap' => [
						'@dektrium/user/views' => '@hscstudio/heart/modules/user/views',
						'@mdm/admin/views' => '@hscstudio/heart/modules/admin/views'
					],					
				],
			]
		);
		
		$view = $app->getView();
		$assets = $view->assetManager->publish('@hscstudio/heart/assets/heart');
		$view->registerCssFile($assets[1].'/css/heart.css', ['yii\bootstrap\BootstrapAsset']);
		$view->registerJsFile($assets[1].'/js/heart.js', ['yii\web\JqueryAsset']);
		
		if(@$this->features['fontawesome']){
			$assets = $view->assetManager->publish('@hscstudio/heart/assets/fontawesome');
			$view->registerCssFile($assets[1].'/css/font-awesome.min.css');
		}
    }
}