<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\AjaxSendForm;

use app\models\Department;
use app\models\Direction;
use app\models\Groups;
//use yii\helpers\ArrayHelper ;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
	/**
     * Вывод страницы с расписанием преподавателей.
     *
     * @return string
     */
	 
	 
    public function actionTeacher()
    {
		return $this->render('teacher');
    }
	/**
     * Аякс загрузка списка кафедр для страницы расписания преподавателей.
     *
     * @return array
     */
	public function actionTeacherAjaxFaculty()
	{
		$faculty_id = Yii::$app->request->post('faculty');		
		$department = Department::find()->where(['id_faculty' => $faculty_id])->asArray()->all();		
		Yii::$app->params['result_fac'] = $department;
		return $this->renderAjax('teacher-ajax-faculty');
	}
	/**
     * Аякс загрузка списка преподавателей кафедры для страницы расписания преподавателей.
     *
     * @return array
     */
	public function actionTeacherAjaxDepartment()
	{
		Yii::$app->params['result_dep'] = Yii::$app->request->post('department');
		$_SESSION['depart_id'] = Yii::$app->request->post('department');
		return $this->renderAjax('teacher-ajax-department');
	}
	/**
     * Аякс загрузка списка учебных недель для страницы расписания преподавателей.
     *
     * @return array
     */
	public function actionTeacherAjaxWeek()
	{
		Yii::$app->params['result_week'] = Yii::$app->request->post('date_week');
		$_SESSION['week_id'] = Yii::$app->request->post('date_week');		
		return $this->renderAjax('teacher-ajax-week');
	}
	/**
     * Аякс загрузка таблицы с расписанием для страницы расписания преподавателей.
     *
     * @return array
     */
	public function actionTeacherAjaxSchedule()
	{
		Yii::$app->params['result_schedule'] = Yii::$app->request->post('teachers_name');
		$_SESSION['teacher_id'] = Yii::$app->request->post('teachers_name');	
		return $this->renderAjax('teacher-ajax-schedule');
	}
	//------------------------------------------------------------------------------------------------------------------------------------------
	//------------------------------------------------------------------------------------------------------------------------------------------
	//------------------------------------------------------------------------------------------------------------------------------------------
	/**
     * Вывод страницы с расписанием для студентов.
     *
     * @return string
     */
    public function actionStudent()
    {
        return $this->render('student');
    }
	/**
     * Аякс загрузка списка учебных направлений для страницы расписания студентов.
     *
     * @return array
     */
	public function actionStudentAjaxDirection()
	{
		$faculty_id = Yii::$app->request->post('faculty');
		$faculty_degree = Yii::$app->request->post('degree');
		$_SESSION['faculty_id'] = $faculty_id;
		$_SESSION['degree'] = $faculty_degree;
		$direction = Direction::find()->where(['id_faculty' => $faculty_id, 'degree' => $faculty_degree])->asArray()->all();		
		Yii::$app->params['result_direction'] = $direction;		
		return $this->renderAjax('student-ajax-direction');
	}
	/**
     * Аякс загрузка списка учебных групп для страницы расписания студентов.
     *
     * @return array
     */
	public function actionStudentAjaxGroup()
	{
		$direction_id = Yii::$app->request->post('direction');
		$_SESSION['direction_id'] = $direction_id;
		$groups = Groups::find()->where(['id_direction' => $direction_id])->asArray()->all();		
		Yii::$app->params['result_groups'] = $groups;		
		return $this->renderAjax('student-ajax-group');
	}
	/**
     * Аякс загрузка списка учебных недель для страницы расписания студентов.
     *
     * @return array
     */
	public function actionStudentAjaxWeek()
	{
		$grp_period_id = Yii::$app->request->post('group');
		$_SESSION['group_id'] = $grp_period_id;	
		Yii::$app->params['result_period'] = $grp_period_id;		
		return $this->renderAjax('student-ajax-week');
	}
	/**
     * Аякс загрузка пдф файла с расписанием для страницы расписания студентов.
     *
     * @return array
     */
	public function actionStudentAjaxSchedule()
	{
		$grp_period_id = Yii::$app->request->post('date_week_student');		
		Yii::$app->params['result_schedule_stud'] = $grp_period_id;		
		return $this->renderAjax('student-ajax-schedule');
	}
}
