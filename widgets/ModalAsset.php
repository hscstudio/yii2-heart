<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace hscstudio\heart\widgets;

use yii\web\AssetBundle;

/**
 * Asset bundle for the Modal Twitter bootstrap JS files.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ModalAsset extends AssetBundle
{
    public $sourcePath = '@hscstudio/heart/assets/heart/';
    public $js = [
        'js/modal.js',
    ];
}
