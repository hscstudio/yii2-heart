<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace hscstudio\heart\widgets;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

use yii\bootstrap\Widget;
use hscstudio\heart\widgets\ModalAsset;

/**
 * Modal renders a Modal TB
 *
```
Place this widget in view
<?= \hscstudio\heart\widgets\Modal::widget(['modalSize'=>'']) ?>

Call this modal with link
<a href='link controller to content' class='modal-heart' title='title of modal'>Show Modal</a>

You can specify source of html data that show
<a href='link controller to content' class='modal-heart' title='title of modal' source='.main'>Show Modal</a>
``` 
 *
 * @see http://almsaeedstudio.com/AdminLTE/pages/widgets.html
 * @author Hafid Mukhlasin <hafidmukhlasin@gmail.com>
 * @since 2.0
 */
class Modal extends Widget
{
    /**
     * @var array the HTML attributes for the widget container tag. The following special options are recognized:
     *
     * - tag: string, defaults to "div", the name of the container tag.
     *
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */

	public $modalId = 'modal-heart';
	public $modalSize = ''; //modal-lg, modal-sm
	public $registerAsset = true;
    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();		
		echo Html::beginTag('div', ['class'=>'modal fade','id'=>$this->modalId,'role'=>"dialog"]);
		echo Html::beginTag('div', ['class'=>'modal-dialog '.$this->modalSize]);
		echo Html::beginTag('div', ['class'=>'modal-content']);
			echo Html::beginTag('div', ['class'=>'modal-header']);
				echo Html::beginTag('button', ['type'=>"button",'class'=>"close", 'data-dismiss'=>"modal"]);
					echo Html::beginTag('span', ['aria-hidden'=>"true"]);
					echo "&times;";
					echo Html::endTag('span');
					echo Html::beginTag('span', ['class'=>"sr-only"]);
					echo "Close";
					echo Html::endTag('span');
				echo Html::endTag('button');
				echo Html::beginTag('h4', ['class'=>"modal-title"]);		
				echo Html::endTag('h4');
			echo Html::endTag('div');
			echo Html::beginTag('div', ['class'=>'modal-body']);
				echo Html::beginTag('div', ['class'=>'content','style'=>'overflow:auto;']);		
				echo Html::endTag('div');
			echo Html::endTag('div');
			/* modal-footer tak hapus. Kayaknya ga pernah kepake. Biar tampilan modalnya bagus, ga ada garis bawah pengganggu
			echo Html::beginTag('div', ['class'=>'modal-footer']);
		
			echo Html::endTag('div');*/
		echo Html::endTag('div');
		echo Html::endTag('div');
		echo Html::endTag('div');
		if($this->registerAsset) ModalAsset::register($this->getView());
    }

    /**
     * Renders the widget.
     */
    public function run()
    {        
        
    }
}
