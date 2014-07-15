<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace hscstudio\heart;
use Yii;
use yii\base\BootstrapInterface;

class Init implements BootstrapInterface{
    public function bootstrap($app){
		$app->set('assetManager' , [
			'class'	=> 'yii\web\AssetManager',
            'bundles' => [
				'backend\assets\AppAsset' => [
					'baseUrl' => null,
					'sourcePath' => '@hscstudio/heart/assets',
					'css'	=> [
						'font-awesome/css/font-awesome.min.css',
						'css/widgets.css',
					],
					'js'	=> [
						'adminlte/js/AdminLTE/app.js',
					],
				],
            ],
            //'linkAssets' => true,
        ]);
    }
}