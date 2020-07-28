<?php
session_start();
/* @var $this yii\web\View */

use yii\helpers\Html;
$_SESSION['display_department'] = '';
$_SESSION['display_week_time'] = '';
$_SESSION['display_teachers'] = '';
$_SESSION['display_schedule'] = 'none';

$_SESSION['overload_protection'] = $_SESSION['overload_protection'] + 1;

$date_week = Yii::$app->params['result_week'];
$_SESSION['date_week'] = $date_week;
$teachers ='';

	$arrContextOptions=array(
		"ssl"=>array(
			"verify_peer"=>false,
			"verify_peer_name"=>false,
		),
	);
	
	try {
		$url = 'https://www.vyatsu.ru/reports/schedule/prepod/'.$date_week;

		$table = file_get_contents($url, false, stream_context_create($arrContextOptions));
		if(!$table) throw new Exception('Официальная страница временно недоступна с сервера. <a href="'.$url.'">Зайти вручную</a><br>');
		else file_put_contents(md5($url), $table);
	} 
	catch (Exception $e) {
		$teachers .= '<b>Ошибка: '.  $e->getMessage(). '</b>';
		$teachers .= 'Последняя доступная версия:<br>';
		$table = file_get_contents(md5($url), false, stream_context_create($arrContextOptions));
	}
	
	if($table) {
		$prepid = -1;
		$islessons = true;
			
		$table = strstr($table, '<TABLE');
		$table = strstr($table, '</TABLE>', true);
		$rows = explode('</TR>', $table, -1);
		$version = trim(strip_tags($rows[0]));

		$teachers .= '<div><i>' .$version . '</i></div>';
	}
	
	if($table) {
		unset($rows[0]);
		$rows = array_values($rows);
		$cells = array();
		$days = array();
		foreach ( $rows as $n=>$datas ) {
			$datas = str_replace("<BR>", " *br*", $datas);
			$cells[$n] = explode('</TD>', $datas, -1);
		}
		unset( $cells[0][0]);
		//$cells[0] = array_values($cells[0]);

		foreach ($cells as $i=>$row)
		{
			if (strpos($cells[$i][0], 'ROWSPAN=') === false) {} else 
			{
				$days[] = trim(strip_tags($cells[$i][0]));
				unset($cells[$i][0]);
				$cells[$i] = array_values($cells[$i]);
			}

			array_walk($cells[$i], function(&$n) { $n = trim(strip_tags($n)); } );
		}

		$teachers = '
				<select size="1" name="teachers_name" id="teachers_name_select">
					<option>Преподаватели</option>';
		// если преподаватель не выбран
		foreach ($cells[0] as $i=>$cell) {
			if ($cell!='') {
				if ($cell!='Интервал') {
					$teachers .= '<option value="'.($i-1).'">'.$cell.'</option>';
				}
			}
		}
		$teachers .= '
				</select>';
	}
	
$_SESSION['teachers_select'] = $teachers;	