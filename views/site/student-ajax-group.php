<?php
session_start();
/* @var $this yii\web\View */
use yii\helpers\Html;
$_SESSION['display_department'] = '';
$_SESSION['display_group'] = '';
$_SESSION['display_date_week_students'] = 'none';
$_SESSION['display_schedule_students'] = 'none';

$_SESSION['overload_protection'] = $_SESSION['overload_protection'] + 1;

$groups = Yii::$app->params['result_groups'];

$group_select = '
		<select size="1" name="group" id="group_name_select">
			<option>Название группы</option>';

foreach ($groups as $group){
	$group_select .= '
			<option value="'.$group['grp_period_id'].'">'.$group['name'].'</option>';
}
$group_select .= '
		</select>';

$_SESSION['group_select'] = $group_select;