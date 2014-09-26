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
	
class SM extends Widget{
	
	public function run(){
		$view = $this->getView();
		$assets = $this->registerScript($view);		
		$script='
		soundManager.setup({
		  url: "'.$assets[1].'/swf/'.'",
		  onready: function() {
			var click1 = soundManager.createSound({
			  id: "a1Sound",
			  url: "'.$assets[1].'/audio/click1.mp3'.'"
			});
			var click2 = soundManager.createSound({
			  id: "a2Sound",
			  url: "'.$assets[1].'/audio/click2.mp3'.'"
			});
			var click3 = soundManager.createSound({
			  id: "a3Sound",
			  url: "'.$assets[1].'/audio/click3.mp3'.'"
			});
			var mouseover = soundManager.createSound({
			  id: "b1Sound",
			  url: "'.$assets[1].'/audio/mouseover.mp3'.'"
			});
			var type1 = soundManager.createSound({
			  id: "c1Sound",
			  url: "'.$assets[1].'/audio/type1.mp3'.'"
			});
			var type2 = soundManager.createSound({
			  id: "c2Sound",
			  url: "'.$assets[1].'/audio/type2.mp3'.'"
			});
			
			$("a, .btn, button").click( function(){ click1.play(); } );
			$("input, textarea, span.select2-chosen, div.bootstrap-switch").mousedown( function(){ click2.play(); } );
			$("input, textarea").keydown( function(){ type1.play(); } );
		  },
		  ontimeout: function() {
			// Hrmm, SM2 could not start. Missing SWF? Flash blocked? Show an error, etc.?
		  }
		});';
		$view->registerJs($script);
	}
	
	public function registerScript($view){		
		$assets = $view->assetManager->publish('@hscstudio/heart/libraries/sm');	
		$view->registerJsFile($assets[1].'/script/soundmanager2-nodebug-jsmin.js', ['yii\web\JqueryAsset']);
		return $assets;
	}
}