<?php

/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2013
 * @package yii2-helpers
 * @version 1.0.0
 */

namespace hscstudio\heart\helpers;

use Yii;

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
			$months = ['Januari','Februari','Maret','April','Mei','Juni','July','Agustus','September','Oktober','November','Desember'];
		else if($monthType==1)	
			$months = array('Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des');
		
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
}