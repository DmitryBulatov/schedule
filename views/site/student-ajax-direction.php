<?php
session_start();
/* @var $this yii\web\View */
use yii\helpers\Html;
$_SESSION['display_direction'] = '';
$_SESSION['display_group'] = 'none';
$_SESSION['display_teachers'] = 'none';
$_SESSION['display_schedule'] = 'none';

$_SESSION['overload_protection'] = $_SESSION['overload_protection'] + 1;


$directions = Yii::$app->params['result_direction'];

$direction_select = '
		<select size="1" name="direction" id="direction_name_select">
			<option>Название направления</option>';

foreach ($directions as $direct){
	$direction_select .= '
			<option value="'.$direct['id'].'">'.$direct['name'].'</option>';
}
$direction_select .= '
		</select>';

$_SESSION['direction_select'] = $direction_select;