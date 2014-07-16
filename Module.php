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
        $view = $app->getView();
		$assets = $view->assetManager->publish('@hscstudio/heart/assets/heart');
		$view->registerCssFile($assets[1].'/css/heart.css', ['yii\bootstrap\BootstrapAsset']);
		$view->registerJsFile($assets[1].'/js/heart.js', ['yii\web\JqueryAsset']);
		
		if(@$this->features['fontawesome']){
			$assets = $view->assetManager->publish('@hscstudio/heart/assets/fontawesome');
			$view->registerCssFile($assets[1].'/css/font-awesome.min.css');
		}
		
		$app->set('assetManager' , [
			'class'	=> 'yii\web\AssetManager',
            'bundles' => [
				'@app\assets\AppAsset' => [
					'baseUrl' => null,
					'css'	=> [],
					'js'=> [],
				],
            ],
        ]);
    }
}