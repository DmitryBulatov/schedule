<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use app\models\Faculty;
use app\models\Department;

$this->title = 'Расписание учебных групп';
$this->params['breadcrumbs'][] = $this->title;

$faculty = Faculty::find()->all();

if ($_SESSION['overload_protection'] != 0) {
	$_SESSION['display_schedule_students'] = 'none';
} else {
	$_SESSION['display_schedule_students'] = '';
	$_SESSION['overload_protection'] = $_SESSION['overload_protection'] + 1;
}
$degrees = [
	'bakalavr' => 'Бакалавриат',
	'specialist' => 'Специалитет',
	'magistr' => 'Магистратура',
	'aspirant' => 'Аспирантура'
];
?>

<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
	
	<p id="faculty_name_student_result"></p>
	<p id="direction_student_result"></p>
	<p id="group_student_result"></p>
    <p id="date_week_student_result"></p>
	
	<form method="post" id="schedule_form" action="">		
		<p>Выберите Степень обучения:</p>
		<p><select size="1" name="degree" id="degree_name_select">
			<option>Степень обучения</option>
			<?php
				foreach ($degrees as $key => $degree){
					if ($key == $_SESSION['degree']) { $selected = 'selected'; } else {$selected = ''; }
					echo '<option id="degree_name" value="'.$key.'" '.$selected.'>'.$degree.'</option>';
				};
			?>
		</select></p>
		<p><select size="1" name="faculty" id="faculty_name_student_select">
			<option>Название факультета</option>
			<?php
				foreach ($faculty as $fac){
					if ($fac['id'] == $_SESSION['faculty_id']) { $selected = 'selected'; } else {$selected = ''; }
					echo '<option id="faculty_name" value="'.$fac['id'].'" '.$selected.'>'.$fac['name_rus'].'</option>';
					
				};
			?>
		</select></p>		
		<p style="display:<?= $_SESSION['display_direction']; ?>"><?= $_SESSION['direction_select']; ?></p>
		<p style="display:<?= $_SESSION['display_group']; ?>"><?= $_SESSION['group_select']; ?></p>
		<p style="display:<?= $_SESSION['display_week_time_students']; ?>"><?= $_SESSION['week_time_students']; ?></p>
	</form>
	
	<p style="display:<?= $_SESSION['display_schedule_students']; ?>"><?= $_SESSION['schedule_students']; ?></p>
	
<script>console.log(<?=$_SESSION['overload_protection'];?>);</script>
</div>
