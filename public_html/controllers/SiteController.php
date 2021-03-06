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
        //$request = Yii::$app->request;
        //$post = $request->post(); 
        $post = $_GET;
        if (isset($post)) {
           return json_encode($this->PushPlaceID($post['LatitudeMin'],$post['LongitudeMin'],$post['LatitudeMax'],$post['LongitudeMax'], $post['PlaceID'])); 
        }
        else {
            return json_encode(0);
        }
    }
	
	public function actionGet() {
		return $this->GetARoute(array(),1);
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
	
	public function GetARoute( $map, $vehicleType = 0) {
		$json = array();
		foreach ($map as $item) {
			$lat = $item['lat'];
			$long = $item['long'];
			$query = CachePlace::find()->where("LatitudeMax >".$lat." AND "."LatitudeMin <".$lat)->andWhere("LongitudeMax > ".$long." AND ". " LatitudeMin < ".$long)->one();
			if ($query) {
				$place = $query->placeID;
				$query2 = CacheRoute::find()->where("PlaceID_A =".$place." OR PlaceID_B=".$place)->one();
				$json[] = $query2->JsonArrayRoute;
			}			
		}
		return json_encode($json);
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
     * Displays homepage.
     *
     * @return string
     */
    public function actionPush()
    {
        //$request = Yii::$app->request;
        //$post = $request->post(); 
        $post = $_GET;
        if (isset($post)) {
           return json_encode($this->PushNewRoute($post['placeIdA'],$post['placeIdB'],$post['arrayJsonText'],$post['vehicleType'])); 
        }
        else {
            return json_encode(0);
        }
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
