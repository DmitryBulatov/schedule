<?php
session_start();
/* @var $this yii\web\View */

use yii\helpers\Html;
$_SESSION['display_department'] = '';
$_SESSION['display_week_time'] = '';
$_SESSION['display_teachers'] = '';
$_SESSION['display_schedule'] = '';

$_SESSION['display_schedule_students'] = '';

$_SESSION['overload_protection'] = 0;

$url_temp = Yii::$app->params['result_schedule_stud'];
$url_temp = 'https://www.vyatsu.ru/reports/schedule/Group/'.$url_temp;
$url_temp = '<object><embed src="'. $url_temp .'" width="100%" height="500" /></object>';

$_SESSION['schedule_students'] = $url_temp;