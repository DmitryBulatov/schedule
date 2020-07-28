<?php
session_start();
/* @var $this yii\web\View */
use yii\helpers\Html;
$_SESSION['display_department'] = '';
$_SESSION['display_group'] = '';
$_SESSION['display_week_time_students'] = '';
$_SESSION['display_schedule_students'] = 'none';

$_SESSION['overload_protection'] = $_SESSION['overload_protection'] + 1;

$period = Yii::$app->params['result_period'];

	/* Отображение пдф файла напрямую
	$temp = "https://www.vyatsu.ru/reports/schedule/Group/12532_2_03022020_16022020.pdf";
	echo '<object><embed src="'. $temp .'" width="100%" height="500" /></object>';
	*/
	
	//----------------------------------------------------------------------------------------------------
	
$arrContextOptions=array(
		"ssl"=>array(
			"verify_peer"=>false,
			"verify_peer_name"=>false,
		),
	);
	
	try {
		$preppage = "https://www.vyatsu.ru/studentu-1/spravochnaya-informatsiya/raspisanie-zanyatiy-dlya-studentov.html";

		$templinkcode = file_get_contents($preppage, false, stream_context_create($arrContextOptions));
		
		if(!$templinkcode) throw new Exception('<a href="'.$preppage.'">Зайти вручную</a><br>');
		else file_put_contents(md5($preppage), $templinkcode);
	} catch (Exception $e) {
		$week_time .= '<b>Ошибка: '. $e->getMessage().'</b>';
		$week_time .= 'Локальная версия страницы:<br>';
		$templinkcode = file_get_contents(md5($preppage), false, stream_context_create($arrContextOptions));
	}
	
	$temp_slug = "/".$period."_2.*pdf/iD";
	preg_match_all($temp_slug, $templinkcode, $matches);
	$link = $matches[0];
	$week_time .= '
		<select size="1" name="date_week_student" id="date_week_student_select">
		<option>Промежуток семестра</option>';
	foreach($link as $value) 
	{
		$text = substr($value,8,17);
		$text = substr($text,0,2).'.'.substr($text,2,2).'.'.substr($text,4,4).' - '.substr($text,9,2).'.'.substr($text,11,2).'.'.substr($text,13,4);
		$date1 = strtotime(substr($text,0,10));
		$date2 = strtotime(substr($text,13,10));
		$datenow =  time();
		$color = ($date1<=$datenow && $date2>=$datenow)?'style="font-weight:bold; color: #ff6600;"':'';
		$week_time .= '<option value="'.$value.'" '.$color.'>'.$text.'</option>';
	}
	$week_time .= '
		</select>';
		
$_SESSION['week_time_students'] = $week_time;