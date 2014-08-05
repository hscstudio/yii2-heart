### Fullcalendar
---
#### BASIC GUIDE
---
```php
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
```

#### ADVANCED USAGE
---
Dynamic event via controller
```php
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
			'events'=> Yii::$app->getUrlManager()->createUrl('site/calendar-events'),
		],
    ]);
	
	// In controller	
	public function actionCalendarEvents()
    {       
        $items = array();
        $model= \backend\models\Admin::find()
				 //->where(['status' => '1'])
				 ->orderBy('id DESC')
				 ->limit(10)
				 ->all();   
        foreach ($model as $value) {
            $items[]=[
                'title'=>$value->username,
                'start'=>date('Y-m-d',$value->created_at),
                'end'=>date('Y-m-d', strtotime('+1 day', $value->updated_at)),
                'color'=>'#CC0000',
                //'allDay'=>true,
                //'url'=>'http://anyurl.com'
            ];
        }
        echo \yii\helpers\Json::encode($items);
    }
```
