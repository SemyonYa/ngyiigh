<?php

namespace frontend\controllers;

use common\models\Company;
use yii\rest\ActiveController;

class CompanyController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;
    public function behaviors() {
        return [
            'corsFilter' => [
                'class' => \yii\filters\Cors::className(),
                'cors' => [
                    // Разрешаем доступ с указанных доменов.
                    'Origin' => ['http://localhost:8080', 'http://site2.com'],
                    'Access-Control-Allow-Origin' => true,
                    // Куки от кроссдоменного запроса
                    // будут установлены браузером только при заголовке
                    // "Access-Control-Allow-Credentials".
                    'Access-Control-Allow-Credentials' => true,
                    // Разрешаем только метод POST.
                    'Access-Control-Request-Method' => ['POST'],
                    'Access-Control-Allow-Headers' => ['Origin', 'Content-Type', 'X-Auth-Token', 'Authorization']
                ],
            ],
        ];
    }

    public function actions() {
//        header('Content-Type: application/json');
//        header('Access-Control-Allow-Origin: *');
//        header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
//        header('Access-Control-Allow-Headers: Overwrite, Destination, Content-Type, Depth, User-Agent, X-File-Size, X-Requested-With, If-Modified-Since, X-File-Name, Cache-Control');
//        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionGet($id = null) {
        if ($id) {
            $company = Company::find()->where(['id' => $id])->asArray()->one();
            return json_encode($company);
        } else {
            $companies = Company::find()->asArray()->all();
            return json_encode($companies);
        }
    }

    public function actionPut() {
        $req = \Yii::$app->request;
        $id = $req->post('id');
        $name = $req->post('name');
        $description = $req->post('description');
        $email = $req->post('email');
        $company = Company::findOne($id);
        $company->name = $name;
        $company->description = $description;
        $company->email = $email;
        $company->save();
//        return true;
    }

    public function actionPost() {
        $req = \Yii::$app->request;
        $name = $req->post('name');
        $description = $req->post('description');
        $email = $req->post('email');
        $company = new Company();
        $company->name = $name;
        $company->description = $description;
        $company->email = $email;
        $company->save();
    }

    public function actionDelete($id) {
        $company = Company::findOne($id);
        $company->delete();
    }

}
