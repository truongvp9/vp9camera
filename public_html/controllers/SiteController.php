<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\Contact;
use app\models\CacheRoute;
use app\models\CachePlace;

class SiteController extends Controller
{
    /**
     * @inheritdoc
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
     * @inheritdoc
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

    public function actionSave() {
        $request = Yii::$app->request;
        //$post = $request->post(); 
        $post = $_GET;
        if (isset($post)) {
           return $this->PushPlaceID($post['LatitudeMin'],$post['LongitudeMin'],$post['LatitudeMax'],$post['LongitudeMax'], $post['PlaceID']); 
        }
        else {
            return 0;
        }
    }
    /**
     * 
     * @param type $placeIdA
     * @param type $placeIdB
     * @param type $arrayJsonText
     * @param type $vehicleType
     * @return type
     */
    
    public function PushNewRoute($placeIdA, $placeIdB, $arrayJsonText, $vehicleType) {
        $route = new CacheRoute(); 
        $route->PlaceID_A = $placeIdA;
        $route->PlaceID_B = $placeIdB;
        $route->JsonArrayRoute = $arrayJsonText;
        $route->TypeVehicle = $vehicleType;
        $result = $route->save();
        return $result;
    }
    /**
     * 
     * @param type $LatitudeMin
     * @param type $LongitudeMin
     * @param type $LatitudeMax
     * @param type $LongitudeMax
     * @param type $PlaceID
     * @return type
     */
    public function PushPlaceID($LatitudeMin, $LongitudeMin,$LatitudeMax, $LongitudeMax, $PlaceID) {
        $place = new CachePlace(); 
        $place->LatitudeMin = $LatitudeMin;
        $place->LongitudeMin = $LongitudeMin;
        $place->LatitudeMax = $LatitudeMax;
        $place->LongitudeMax = $LongitudeMax;
        $place->PlaceID = $PlaceID;
        $result = $place->save();
        return $result;
    }
    
    /** 
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $request = Yii::$app->request;
        //$post = $request->post(); 
        $post = $_GET;
        if (isset($post)) {
           return $this->PushNewRoute($post['placeIdA'],$post['placeIdB'],$post['arrayJsonText'],$post['vehicleType']); 
        }
        else {
            return 0;
        }
        //echo "aaa";
        //return $this->render('index');
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
}
