<?php
namespace hscstudio\heart\components;

use yii\db\ActiveRecord;
use yii\base\Behavior;

class HistoryBehavior extends Behavior
{
	public $historyClass;
	public $attributes = [];
	public $primaryField='id';
	public $revisionField='revision';
	public $revision;
	
    public function events()
    {
        return [
			ActiveRecord::EVENT_AFTER_INSERT => 'afterUpdate',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterUpdate',
        ];
    }
	
	public function afterUpdate($event)
    {
        /* @var $object \yii\db\ActiveRecord */
		$object = $event->sender;
        $historyClass = $this->historyClass;
        $params = $object->getPrimaryKey(true);
		$revisionField = $this->revisionField;
		$primaryField = $this->primaryField;
		if(!empty($this->revision)){
			$revision=$this->revision;
		}
		else{
			$revision=(int)$historyClass::getRevision($object->$primaryField);
		}		
       
		if($object->create_revision){
			$objectHistory = new $historyClass([
				$revisionField => $revision+1
			]);
		}
		else{
			$params[$revisionField] = $revision;
			// CREATE HISTORY
			$objectHistory = $historyClass::find()
				->where($params)
				->one();
			if ($objectHistory === null) {
				// CREATE HISTORY
				$objectHistory = new $historyClass([
					$primaryField => $params[$primaryField],
					$revisionField => '0'
				]);
			}
        }
		
		
		$objectHistory->attributes = $object->attributes;
        if ($objectHistory->save()){

		}
    }
}