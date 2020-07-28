<?php
session_start();
/* @var $this yii\web\View */

use yii\helpers\Html;
$display_timetable ='';
$_SESSION['display_department'] = '';
$_SESSION['display_week_time'] = '';
$_SESSION['display_teachers'] = 'none';
$_SESSION['display_schedule'] = 'none';

$_SESSION['overload_protection'] = $_SESSION['overload_protection'] + 1;

$department_id = Yii::$app->params['result_dep'];

$_SESSION['department_id'] = $department_id;

	$arrContextOptions=array(
		"ssl"=>array(
			"verify_peer"=>false,
			"verify_peer_name"=>false,
		),
	);
	
	try {
		$preppage = "https://www.vyatsu.ru/studentu-1/spravochnaya-informatsiya/teacher.html";

		$templinkcode = file_get_contents($preppage, false, stream_context_create($arrContextOptions));
		
		if(!$templinkcode) throw new Exception('<a href="'.$preppage.'">Зайти вручную</a><br>');
		else file_put_contents(md5($preppage), $templinkcode);
	} catch (Exception $e) {
		$week_time .= '<b>Ошибка: '. $e->getMessage().'</b>';
		$week_time .= 'Локальная версия страницы:<br>';
		$templinkcode = file_get_contents(md5($preppage), false, stream_context_create($arrContextOptions));
	}
	
	$dep_temp_id_slug = "/".$department_id."_2.*html/iD";
	preg_match_all($dep_temp_id_slug, $templinkcode, $matches);
	$link = $matches[0];
	$week_time .= '
		<select size="1" name="date_week" id="date_week_select">
		<option>Промежуток семестра</option>';
	foreach($link as $value) 
	{
		$text=str_replace('_',' - ',substr($value,6));
		$text = substr($text,0,2).".".substr($text,2,2).".".substr($text,4,9).".".substr($text,13,2).".".substr($text,15,4);
		$date1 = strtotime(substr($text,0,10));
		$date2 = strtotime(substr($text,13,10));
		$datenow =  time();
		$color = ($date1<=$datenow && $date2>=$datenow)?'style="font-weight:bold; color: #ff6600;"':'';
		$week_time .= '<option value="'.$value.'" '.$color.'>'.$text.'</option>';
	}
	$week_time .= '
		</select>';
	
		
$_SESSION['week_time_select'] = $week_time;
	?>