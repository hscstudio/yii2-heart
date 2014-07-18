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
use yii\bootstrap\BootstrapPluginAsset;

/**
 * Box renders a Box HTML component.
 *
 * Any content enclosed between the [[begin()]] and [[end()]] calls of Box
 * is treated as the content of the box. 
```
Box::begin([
	'type'=>'small', // ,small, solid, tiles
	'bgColor'=>'aqua', // , aqua, green, yellow, red, blue, purple, teal, maroon, navy, light-blue
	'options' => [],
	'headerOptions' => [
		'button' => ['collapse','remove'],
		'position' => 'right', //right, left
		'color' => '', //primary, info, warning, success, danger
		'class' => '',
	],
	'header' => '',
	'bodyOptions' => [],
	'icon' => '',
	'link' => '',
	'footerOptions' => [],
	'footer' => 'More info <i class="fa fa-arrow-circle-right"></i>',
]);
?>
	Your content of the box here
<?php
Box::end();
``` 
 *
 * @see http://almsaeedstudio.com/AdminLTE/pages/widgets.html
 * @author Hafid Mukhlasin <hafidmukhlasin@gmail.com>
 * @since 2.0
 */
class Box extends Widget
{
    /**
     * @var array the HTML attributes for the widget container tag. The following special options are recognized:
     *
     * - tag: string, defaults to "div", the name of the container tag.
     *
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $options = [];
	public $type = 'default'; // default, small, solid, tiles
	public $bgColor = 'default'; // , aqua, green, yellow, red, blue, purple, teal, maroon
							// navy, light-blue, 	
	public $header = '';
	public $headerOptions = [
		'button' => ['collapse','remove'],
		'position' => 'right', //right, left
		'color' => 'default', //default, primary, info, warning, success, danger
		'class' => '',
	];
	
	public $bodyOptions = [];
	
	public $footerOptions = [];
	public $footer = '';
    
	public $icon = 'fa fa-rocket';
	public $link = '#';

    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();
		
		if($this->type=='small'){
			Html::addCssClass($this->options, 'small-box');
			if(!empty($this->bgColor)){ // aqua, green, yellow, red, blue, purple, teal, maroon
				Html::addCssClass($this->options, 'bg-'.$this->bgColor);
			}			
			Html::addCssClass($this->bodyOptions, 'inner');
		}
		elseif($this->type=='solid'){
			Html::addCssClass($this->options, 'box box-solid box-'.$this->headerOptions['color']);
			Html::addCssClass($this->headerOptions, 'box-header');			
			Html::addCssClass($this->bodyOptions, 'box-body');
		}
		elseif($this->type=='tiles'){
			Html::addCssClass($this->options, 'box box-solid bg-'.$this->bgColor);
			Html::addCssClass($this->headerOptions, 'box-header');			
			Html::addCssClass($this->bodyOptions, 'box-body');
		}
		else{
			Html::addCssClass($this->options, 'box box-'.$this->headerOptions['color']);
			Html::addCssClass($this->headerOptions, 'box-header');			
			Html::addCssClass($this->bodyOptions, 'box-body');
		}
		
		echo Html::beginTag('div', $this->options);
			if($this->type=='small'){
			
			}
			else{
				echo Html::beginTag('div', ['class'=>$this->headerOptions['class']]);
				echo Html::beginTag('h3', ['class'=>'box-title']);
				echo $this->header;
				echo Html::endTag('h3');
				if($this->type!='tiles'){
					echo Html::beginTag('div', ['class'=>'box-tools pull-'.$this->headerOptions['position']]);
						if(in_array('collapse',$this->headerOptions['button'])){
							?>
							<button class="btn btn-<?php echo $this->headerOptions['color']; ?> btn-sm" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
							<?php
						}
						if(in_array('remove',$this->headerOptions['button'])){
							?>
							<button class="btn btn-<?php echo $this->headerOptions['color']; ?> btn-sm" data-widget="remove" data-toggle="tooltip" title="" data-original-title="Remove"><i class="fa fa-times"></i></button>
							<?php
						}
					echo Html::endTag('div');
				}
				echo Html::endTag('div');
			}
			echo Html::beginTag('div', $this->bodyOptions);	
    }

    /**
     * Renders the widget.
     */
    public function run()
    {        
			
			echo Html::endTag('div');
			if($this->type=='small'){
				echo Html::beginTag('div', ['class'=>'icon']);
					echo '<i class="'.$this->icon.'"></i>';
				echo Html::endTag('div');
				
				Html::addCssClass($this->footerOptions, 'small-box-footer');
				echo Html::a(
					$this->footer, 
					$this->link, 
					$this->footerOptions);
			}
			else if($this->type=='tiles'){
			
			}
			else{				
				Html::addCssClass($this->footerOptions, 'box-footer');
				echo Html::beginTag('div', $this->footerOptions);
					echo $this->footer;
				echo Html::endTag('div');
			}
		echo Html::endTag('div');
        BootstrapPluginAsset::register($this->getView());
    }
}
