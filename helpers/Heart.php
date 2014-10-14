<?php

/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2013
 * @package yii2-helpers
 * @version 1.0.0
 */

namespace hscstudio\heart\helpers;

use Yii;
use backend\models\ObjectReference;
use backend\models\ObjectFile;
use backend\models\File;
use backend\models\ObjectPerson;
use yii\helpers\ArrayHelper;
/**
 * Provides implementation for various helper functions
 *
 * @author Hafid Mukhlasin <hafidmukhlasin@gmail.com>
 * @since 2.0
 */
class Heart
{


    /**
     * Generates abjad list column.
     *
     * @param integer $start 
     * @param integer $end 
     * 
     * Example(s):
     * ~~~
     * echo Heart::abjad(0); // [A]
     * echo Heart::abjad(28); // [AB]
     * echo Heart::abjad(1,3); // [B,C,D]
     * ~~~
     *
     * @see Excel columns
     */
	
	public static function abjad($start=0,$end=0){
		$abjadList = [];		
		for($i=65;$i<=90;$i++){
			$abjadList[]=chr($i);
			if($i==90){
				for($j=65;$j<=90;$j++){
					for($k=65;$k<=90;$k++){
						$abjadList[]=chr($j).chr($k);
					}
				}
			}
		}
		
		$abjad = [];
		if($end<$start) $end=$start;
		for($x=$start;$x<=$end;$x++){
			if($x>=702) break;
			$abjad[$x] = $abjadList[$x];
		}
		return $abjad;
	}
	
	public static function terbilang($x=1){
		if($x>2147483647) return $x;
		$abil = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
		if ($x < 12)	return " " . $abil[$x];
		elseif ($x < 20)	return self::terbilang($x - 10) . " belas";
		elseif ($x < 100)	return self::terbilang($x / 10) . " puluh" . self::terbilang($x % 10);
		elseif ($x < 200)	return " seratus" . self::terbilang($x - 100);
		elseif ($x < 1000)	return self::terbilang($x / 100) . " ratus" . self::terbilang($x % 100);
		elseif ($x < 2000)	return " seribu" . self::terbilang($x - 1000);
		elseif ($x < 1000000)	return self::terbilang($x / 1000) . " ribu" . self::terbilang($x % 1000);
		elseif ($x < 1000000000)	return self::terbilang($x / 1000000) . " juta" . self::terbilang($x % 1000000);
		elseif ($x < 1000000000000)	return self::terbilang($x / 1000000000) . " milyar" . self::terbilang($x % 1000000000);
	}
	
	public static function imageResize($src, $dst, $width, $height, $crop=0){
		if(!list($w, $h) = getimagesize($src)) return "Unsupported picture type!";
		$type = strtolower(substr(strrchr($src,"."),1));
		if($type == 'jpeg') $type = 'jpg';
		switch($type){
			case 'bmp': $img = imagecreatefromwbmp($src); break;
			case 'gif': $img = imagecreatefromgif($src); break;
			case 'jpg': $img = imagecreatefromjpeg($src); break;
			case 'png': $img = imagecreatefrompng($src); break;
			default : return "Unsupported picture type!";
		}

		// resize
		if($crop){
			if($w < ($width) or $h < ($height)) return " Foto terlalu kecil!";
			$ratio = max($width/$w, $height/$h);
			$h = $height / $ratio;
			$x = ($w - $width / $ratio) / 2;
			$w = $width / $ratio;
		}
		else{
			if($w < ($width) and $h < ($height)) return " Foto terlalu kecil!";
			$ratio = min($width/$w, $height/$h);
			$width = $w * $ratio;
			$height = $h * $ratio;
			$x = 0;
		}

		$new = imagecreatetruecolor($width, $height);
		// preserve transparency
		if($type == "gif" or $type == "png"){
			imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
			imagealphablending($new, false);
			imagesavealpha($new, true);
		}

		imagecopyresampled($new, $img, 0, 0, $x, 0, $width, $height, $w, $h);

		switch($type){
			case 'bmp': imagewbmp($new, $dst); break;
			case 'gif': imagegif($new, $dst); break;
			case 'jpg': imagejpeg($new, $dst); break;
			case 'png': imagepng($new, $dst); break;
		}
		return true;
	}
	
	public static function twodate($start='',$end='',$monthType=0,$yearType=0,$delimiter=' ',$separator=' s.d '){ // $monthType/$yearType 0 = Januari/2014, 1 = Jan/04, 2 = ''
		$months = ['','','','','','','','','','','',''];
		if($monthType==0)
			$months = ['','Januari','Februari','Maret','April','Mei','Juni','July','Agustus','September','Oktober','November','Desember'];
		else if($monthType==1)	
			$months = ['','Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
		
		$showdate="";
		if(empty($start)) $start = date('Y-m-d');
		if(empty($end)) $end = $start;
		
		$starts = explode('-',$start);
		$ends = explode('-',$end);
		
		$year1 = $starts[0];
		if($yearType==1) $year1=substr($year1,2,2);
		else if($yearType==2) $year1='';
		
		$year2 = $ends[0];
		if($yearType==1) $year2=substr($year2,2,2);
		else if($yearType==2) $year2='';
		
		$month1=$months[(int)$starts[1]];
		$month2=$months[(int)$ends[1]];
		
		$day1=(int)$starts[2];
		$day2=(int)$ends[2];
		
		if($year1!=$year2){
			return $day1.$delimiter.$month1.$delimiter.$year1.$separator.$day2.$delimiter.$month2.$delimiter.$year2;
		}
		else{
			if($month1!=$month2){
				return $day1.$delimiter.$month1.$separator.$day2.$delimiter.$month2.$delimiter.$year1;
			}
			else{
				if($day1!=$day2){
					return $day1.$separator.$day2.$delimiter.$month1.$delimiter.$year1;
				}
				else{
					return $day1.$delimiter.$month1.$delimiter.$year1;
				}
			}
		}
	}
	
	public function upload($instance_file, $object='person', $object_id, $file, $object_file, $type='photo', $resize=false,
		$current_file='',$thumb = false){
		if($object_file==null) $object_file = new ObjectFile();
		if($file==null) $file = new File();
		$ext = end((explode(".", $instance_file->name)));
		$filenames = uniqid() . '.' . $ext;				
		$file->file_name = $filenames;
		$path = '';
		if(isset(Yii::$app->params['uploadPath'])){
			$path = Yii::$app->params['uploadPath'].'/'.$object.'/'.$object_id.'/';
		}
		else{
			$path = Yii::getAlias('@file').'/'.$object.'/'.$object_id.'/';
		}
		@mkdir($path, 0755, true);
		@chmod($path, 0755);
		if(isset($current_file)){
			@unlink($path . $current_file);
			@unlink($path . 'thumb_'. $current_file);
		}
		
		if(isset($filenames)){
			$instance_file->saveAs($path.$filenames);
			if ($resize) 
				\hscstudio\heart\helpers\Heart::imageResize($path.$filenames, $path. 'thumb_'. $filenames,148,198,0);
			if(!isset($file->name)) $file->name = $filenames;
			if(!isset($file->status)) $file->status=1;
			$file->save();

			$object_file->object = $object; 
			$object_file->object_id = $object_id; 
			$object_file->type = $type; 
			$object_file->file_id = $file->id; 
			$object_file->save();
			return true;
		}
		return false;
	}
	
	public function unlink($object_file, $thumb = false){
		
		$current_file = $object_file->file->file_name;
		if(isset($current_file)){
			$path = '';
			if(isset(Yii::$app->params['uploadPath'])){
				$path = Yii::$app->params['uploadPath'].'/'.$object_file->object.'/'.$object_file->object_id.'/';
			}
			else{
				$path = Yii::getAlias('@file').'/'.$object_file->object.'/'.$object_file->object_id.'/';
			}
			@chmod($path, 0755);		
			if(file_exists($path . $current_file)){
				@unlink($path . $current_file);
				if($thumb) @unlink($path . 'thumb_'. $current_file);
			}
			$object_file->delete();
			$object_file->file->delete();			
			return true;
		}
		return false;
	}			
			
	public function objectReference($object_reference,$reference_id,$object,$object_id,$type){
		if($object_reference==null){
			$object_reference = new ObjectReference();
		}
		$object_reference->reference_id = $reference_id;
		$object_reference->object = $object; 
		$object_reference->object_id = $object_id; 
		$object_reference->type = $type; 
		if($object_reference->save()) return true;
		else return false;
	}
	
	public function objectPerson($object_person,$person_id,$object,$object_id,$type){
		if($object_person==null){
			$object_person = new ObjectPerson();
		}
		
		if($person_id==0){
			ObjectPerson::deleteAll(
				'type = :type AND object = :object AND object_id = :object_id' , 
				[':type' => $type, ':object' => $object, ':object_id' => $object_id]
			);
		}
		else{
			$object_person->person_id = $person_id;
			$object_person->object = $object; 
			$object_person->object_id = $object_id; 
			$object_person->type = $type; 
			if($object_person->save()) return true;
			else return false;
		}
	}
	
	public function OrganisationAuthorized($code=[],$chairman=[0],$array_map=false){
		$object_person = \backend\models\Person::find()
			//->select(['id','name'])
			->where([
				'id'=>\backend\models\Employee::find()
					->select('person_id')
					->where([
						'organisation_id'=>\backend\models\Organisation::find()
							->select('ID')
							->where([
								'KD_UNIT_ORG'=>$code,
							])
							->column(),
						'chairman' => $chairman, 
					])
					->currentSatker()
					->column(),
			])		
			->active();
		
		if($array_map){
			$data = ArrayHelper::map($object_person->asArray()->all(),'id','name');
			return $data;
		}
		else{
			$object_person->count();
			return ($object_person->count()>0)?true:false;
		}
	}
}