<?php
session_start();
/* @var $this yii\web\View */
use yii\helpers\Html;
$_SESSION['display_department'] = '';
$_SESSION['display_week_time'] = 'none';
$_SESSION['display_teachers'] = 'none';
$_SESSION['display_schedule'] = 'none';

$_SESSION['display_group'] = 'none';
$_SESSION['display_date_week_students'] = 'none';
$_SESSION['display_schedule_students'] = 'none';

$_SESSION['overload_protection'] = $_SESSION['overload_protection'] + 1;

$department = Yii::$app->params['result_fac'];

$department_select = '
		<select size="1" name="department" id="department_name_select">
			<option>Название кафедры</option>';
var_dump($_SESSION);
foreach ($department as $depart){
	$department_select .= '
			<option value="'.$depart['id'].'">'.$depart['name'].'</option>';
			/*
	$_SESSION['depart_slug'] = $depart['slug_schedule'];
	$_SESSION['faculty_id'] = $depart['id_faculty'];*/
}
$department_select .= '
		</select>';

$_SESSION['department_select'] = $department_select;