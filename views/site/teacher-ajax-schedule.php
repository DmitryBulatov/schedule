<?php
session_start();
/* @var $this yii\web\View */

use yii\helpers\Html;
use app\models\Comments;

$_SESSION['display_department'] = '';
$_SESSION['display_week_time'] = '';
$_SESSION['display_teachers'] = '';
$_SESSION['display_schedule'] = '';

$prepid = Yii::$app->params['result_schedule'];

$_SESSION['overload_protection'] = 0;

$schedule = '<table border="1">';

$arrContextOptions=array(
	"ssl"=>array(
		"verify_peer"=>false,
		"verify_peer_name"=>false,
	),
);

$url_date = $_SESSION['date_week'];

$url = 'https://www.vyatsu.ru/reports/schedule/prepod/'.$url_date;

$table = file_get_contents($url, false, stream_context_create($arrContextOptions));
if(!$table) throw new Exception('Официальная страница временно недоступна с сервера. <a href="'.$url.'">Зайти вручную</a><br>');
else file_put_contents(md5($url), $table);



if($table) {
	//$prepid = -1;
		
	$table = strstr($table, '<TABLE');
	$table = strstr($table, '</TABLE>', true);
	$rows = explode('</TR>', $table, -1);
	$version = trim(strip_tags($rows[0]));
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
	$cells[0] = array_values($cells[0]);
	$cells[0][0] = 'Преподаватель';

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

	foreach ($cells as $i=>$row) 
	{
		if($i > 12*7) break;
		if ($i%7 == 1) //если новый день, печатаем заголовок
		{
			$rowspan_helper = false;
			$rowspan = 0;
			$temp = $i+7;
			for ($x = $i; $x <= $temp; $x++) {
				if ($cells[$x][$prepid] !='') { $rowspan++; }
			}
			$rowspan_echo = ($rowspan==0)?'':' rowspan="'.$rowspan.'"';
			$schedule .= '<tr><td> <b'.((strpos($days[$i/7], date('d.m.y')) === false)?'>'. $days[$i/7]:' style="color:red;">'.$days[$i/7]).'</b></td>'; 
		}
		if ($row[$prepid] !='') // если в выбранное время у преподавателя стоит пара
		{
			$text = '';
			$row[$prepid] = str_replace("*br*", "<br>", $row[$prepid]);
			$words = explode(" ", $row[$prepid]);
			foreach ($words as $word) {
				if((substr_count($word,'-') == 1) && (strlen($word) < 8) && FALSE){
						$kabs = explode("-", $word);
						$text .= '<a href="room.php?bildid='.$kabs[0].'">'.$word.'</a>';
				} else {
					$word_temp = explode("(https:", $word);
					$text .= $word_temp[0] . ' ';
				}
			}
			
			if($i!=0){
				$other_words = explode("https:", $row[$prepid]);
				$other_text = '<br><a href="https:'.$other_words[1].'"> Ссылка на занятие в TEAMS</a>';
			} else { $other_text = ''; }
			if ($rowspan_helper) {
				$schedule .= '<td></td>';
			}
			$rowspan_helper = true;
			$schedule .= '<td><b>'.$row[0].'</b></td>';
			$schedule .= '<td><div> '.$text.'</div><i> '.$other_text.'</i></td>'; 
			
			$comment_date = str_replace('&nbsp;',' ', $days[$i/7].''.$row[0]);
			$comment = Comments::find()->where(['slug' => $url_date, 'date' => $comment_date, 'teacher_id' => $prepid])->asArray()->all();
			if (!empty($comment[0]['text'])){
				$schedule .=  '<td> Коментарий к занятию:<br><div style="color:green;">'.$comment[0]['text'].'<div></td></tr>';
			} else {
				$schedule .=  '</tr>';
			}
		}
	}
}
$_SESSION['schedule'] = $schedule;