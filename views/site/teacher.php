<?php
session_start();
/* @var $this yii\web\View */

use yii\helpers\Html;
use app\models\Faculty;
use app\models\Department;

$this->title = 'Занятость преподавателей';
$this->params['breadcrumbs'][] = $this->title;

$faculty = Faculty::find()->asArray()->all();

if ($_SESSION['overload_protection'] > 0) {
	$_SESSION['display_schedule'] = 'none';
} else {
	$_SESSION['display_schedule'] = '';
	$_SESSION['overload_protection'] = $_SESSION['overload_protection'] + 1;
}

?>

<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
	
	<p id="faculty_name_result"></p>
	<p id="department_name_result"></p>
	<p id="date_week_result"></p>
	<p id="teachers_name_result"></p>
    
	<form method="post" id="schedule_form" action="">		
		<p>Выберите факультет и группу:</p>
		
		<p><select size="1" name="faculty" id="faculty_name_select">
			<option>Название факультета</option>
			<?php
				foreach ($faculty as $fac){
					echo '<option id="faculty_name" value="'.$fac['id'].'">'.$fac['name_rus'].'</option>';
					
				};
			?>
		</select></p>		
		<p style="display:<?= $_SESSION['display_department']; ?>"><?= $_SESSION['department_select']; ?></p>
		<p style="display:<?= $_SESSION['display_week_time']; ?>"><?= $_SESSION['week_time_select']; ?></p>
		<p style="display:<?= $_SESSION['display_teachers']; ?>"><?= $_SESSION['teachers_select']; ?></p>
		
	</form>
	
<script type="javascript">
	console.log(<?=$_SESSION['overload_protection'];?>);
<?php if (!empty($_SESSION['depart_id'])) { ?>
	$("#department_name_select option[value=<?=$_SESSION['depart_id'];?>]").prop('selected', true);
<?php } ?>
<?php if (!empty($_SESSION['week_id'])) { ?>
	$("#date_week_select option[value=<?=$_SESSION['week_id'];?>]").prop('selected', true);
<?php } ?>
<?php if (!empty($_SESSION['teacher_id'])) { ?>
	$("#teachers_name_select option[value=<?=$_SESSION['teacher_id'];?>]").prop('selected', true);
<?php } ?>
</script>
	<div style="display:<?= $_SESSION['display_schedule']; ?>"><?= $_SESSION['schedule']; ?></div>

</div>
