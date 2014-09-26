<?php 
/* 
http://arshaw.com/fullcalendar/
http://www.yiiframework.com/extension/yii2-fullcalendar/ 
*/

namespace hscstudio\heart\widgets;
use Yii;
use yii\web\View;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\bootstrap\Widget;

/*
	// BASIC GUIDE
	// ===========
	use hscstudio\heart\widgets\FullCalendar;
	echo FullCalendar::widget([
		'options'=>[
			'id'=>'calendar',
			'header'=>[
				'left'=>'prev,next today',
				'center'=>'title',
				'right'=>'month,agendaWeek,agendaDay',
			],
			'editable'=> true,
			'events'=> [
				[
					'title'=> 'All Day Event',
					'start'=> '2014-07-01'
				],
				[
					'title'=> 'ALong Event',
					'start'=> '2014-07-03',
					'end'=> '2014-07-05',
					'url'=> 'http://google.com/',
				],
				[
					'id'=> 999,
					'title'=> 'Repeating Event',
					'start'=> '2014-07-16T16:00:00',
				],
				[
					'title'=> 'Meeting',
					'start'=> '2014-07-12T10:30:00',
					'end'=> '2014-07-12T10:30:00',
				],
			],
		],
    ]);
	
	// DYNAMIC EVENTS
	// ===========
	
	// In view
	echo hscstudio\heart\widgets\FullCalendar::widget([
		'options'=>[
			'id'=>'calendar',
			'header'=>[
				'left'=>'prev,next today',
				'center'=>'title',
				'right'=>'month,agendaWeek,agendaDay',
			],
			'editable'=> true,
			'eventLimit'=>true, // allow "more" link when too many events
			'events'=> Yii::$app->getUrlManager()->createUrl('site/calendar-events'),
			'loading'=> 'function(bool) {
				//
			}',
		],
    ]);
	
	// In controller	
	public function actionCalendarEvents()
    {       
        $items = array();
        $model= \backend\models\Admin::find()
				 //->where(['status' => '>0'])
				 ->orderBy('id DESC')
				 ->limit(10)
				 ->all();   
        foreach ($model as $value) {
            $items[]=[
                'title'=>$value->username,
                'start'=>date('Y-m-d',strtotime($value->created_at)),
                'end'=>date('Y-m-d', strtotime('+1 day', strtotime($value->updated_at))),
                'color'=>'#CC0000',
                //'allDay'=>true,
                //'url'=>'http://anyurl.com'
            ];
        }
        echo \yii\helpers\Json::encode($items);
    }
*/
	
class FullCalendar extends Widget{
	public $options=[];
	public $htmlOptions=[];
	
	public function run(){
		if (!isset($this->options['id'])) {
			$this->options['id'] = $this->getId();
		}
		$this->htmlOptions['id']= $id = $this->options['id'];	
		$view = $this->getView();
		$this->registerScript($view);		
		echo Html::beginTag('div',$this->htmlOptions);
		echo Html::endTag('div');		
		$encodeoptions=Json::encode($this->options);
		$view->registerJs("$('#$id').fullCalendar($encodeoptions);");
	}
	
	public function registerScript($view){		
		$fullcalendar = $view->assetManager->publish('@hscstudio/heart/assets/fullcalendar');
		$view->registerCssFile($fullcalendar[1].'/fullcalendar.css', ['yii\web\JqueryAsset']);
		//$view->registerCssFile($fullcalendar[1].'/fullcalendar.print.css');		
		$view->registerJsFile($fullcalendar[1].'/lib/moment.min.js', ['yii\web\JqueryAsset']);
		$view->registerJsFile($fullcalendar[1].'/lib/jquery-ui.custom.min.js', ['yii\web\JqueryAsset']);
		$view->registerJsFile($fullcalendar[1].'/fullcalendar.min.js', ['yii\web\JqueryAsset']);
	}
}