/* Article FructCode.com */

	$('.hide_or_show_result').click(function () {
		$('.result_class').toggle();
	});
	
$( document ).ready(function() {
	$("select#faculty_name_select").change(function(){
		$.ajax({
			url:		"teacher-ajax-faculty",
			type:		"POST",
			dataType:	"text",
			data: $("#schedule_form").serialize(),
			success: function(response) {
				let selectedValue = faculty_name_select.value;
				location.reload();
			},
			error: function(response) {
				$('#faculty_name_result').html('Ошибка. Данные о факультете не отправлены.');
			}
		});
	});
	$("select#department_name_select").change(function(){
		$.ajax({
			url:		"teacher-ajax-department",
			type:		"POST",
			dataType:	"text",
			data: $("#schedule_form").serialize(),
			success: function(response) {
				let selectedValue = department_name_select.value;
				location.reload();
			},
			error: function(response) {
				$('#department_name_result').html('Ошибка. Данные о группе не отправлены.');
			}
		});
	});
	$("select#date_week_select").change(function(){
		$.ajax({
			url:		"teacher-ajax-week",
			type:		"POST",
			dataType:	"text",
			data: $("#schedule_form").serialize(),
			success: function(response) {
				let selectedValue = date_week_select.value;
				location.reload();
			},
			error: function(response) {
				$('#date_week_result').html('Ошибка. Данные о выбранной неделе не отправлены.');
			}
		});
	});
	$("select#teachers_name_select").change(function(){
		$.ajax({
			url:		"teacher-ajax-schedule",
			type:		"POST",
			dataType:	"text",
			data: $("#schedule_form").serialize(),
			success: function(response) {
				let selectedValue = teachers_name_select.value;
				location.reload();
			},
			error: function(response) {
				$('#teachers_name_result').html('Ошибка. Данные о преподавателе не отправлены.');
			}
		});
	});
	//----------
	$("select#faculty_name_student_select").change(function(){
		$.ajax({
			url:		"student-ajax-direction",
			type:		"POST",
			dataType:	"text",
			data: $("#schedule_form").serialize(),
			success: function(response) {
				location.reload();
			},
			error: function(response) {
				$('#faculty_name_student_result').html('Ошибка. Данные о степени обучения и факультете не отправлены.');
			}
		});
	});	
	$("select#direction_name_select").change(function(){
		$.ajax({
			url:		"student-ajax-group",
			type:		"POST",
			dataType:	"text",
			data: $("#schedule_form").serialize(),
			success: function(response) {
				let selectedValue = direction_name_select.value;
				location.reload();
			},
			error: function(response) {
				$('#direction_student_result').html('Ошибка. Данные о направлении обучения не отправлены.');
			}
		});
	});
	$("select#group_name_select").change(function(){
		$.ajax({
			url:		"student-ajax-week",
			type:		"POST",
			dataType:	"text",
			data: $("#schedule_form").serialize(),
			success: function(response) {
				let selectedValue = group_name_select.value;
				location.reload();
			},
			error: function(response) {
				$('#group_student_result').html('Ошибка. Данные о группе не отправлены.');
			}
		});
	});
	$("select#date_week_student_select").change(function(){
		$.ajax({
			url:		"student-ajax-schedule",
			type:		"POST",
			dataType:	"text",
			data: $("#schedule_form").serialize(),
			success: function(response) {
				let selectedValue = group_name_select.value;
				location.reload();
			},
			error: function(response) {
				$('#date_week_student_result').html('Ошибка. Данные о расписании не отправлены.');
			}
		});
	});
});