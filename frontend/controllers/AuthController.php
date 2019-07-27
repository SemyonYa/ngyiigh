<?php

namespace frontend\controllers;

use common\models\User;
use common\models\Userng;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;

class AuthController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;

    public function behaviors()
    {
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

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionIsAdmin($login)
    {
        $user = Userng::findOne(['login' => $login]);
//        echo '---';
//        echo $user->role === 'admin';
        if ($user) {
            return json_encode(1);
        } else {
            return json_encode(0);
        }
    }

    public function actionLogin()
    {
        $req = \Yii::$app->request;
        $login = $req->post('login');
        $password = $req->post('password');
        $user = Userng::findOne(['login' => $login]);
        if (!$user) return json_encode('');
        if (\Yii::$app->security->validatePassword($password, $user->password)) {
            $tokens = $this->generateTokens($user);
            $jwt1 = $tokens['jwt1'];
            $jwt2 = $tokens['jwt2'];
            $user->jwt2 = $jwt2;
            $user->save();
            return json_encode(['jwt1' => $jwt1, 'jwt2' => $jwt2,]);
        } else {
            return json_encode('');
        }
    }

    public function actionUpdateTokens() {
        $token2 = \Yii::$app->request->post('token2');
        $jwt = (new Parser())->parse((string)$token2);
        $user_id = $jwt->getClaim('uid');
        $expire = date($jwt->getClaim('exp'));
        $user = Userng::findOne($user_id);
        if (date('Y-m-d H:i:s', $expire) > date('Y-m-d H:i:s')) { //  && $user->jwt2 === (string)$token2
            return json_encode($this->generateTokens($user));
        }
    }

    public function generateTokens($user) {
        $jwt1 = (new Builder())
            ->issuedBy($_SERVER['HTTP_HOST'])
            ->issuedAt(time())
            ->expiresAt(time() + 60) //86400)
            ->withClaim('uid', $user->id)
            ->withClaim('role', $user->role)
            ->getToken();
        $jwt2 = (new Builder())
            ->expiresAt(time() + 180)//432000)
            ->withClaim('uid', $user->id)
            ->getToken();
        return [
            'jwt1' => $jwt1->__toString(),
            'jwt2' => $jwt2->__toString(),
        ];
    }

    public function actionQwe()
    {
//        $user = Userng::findOne(['login' => $u]);
//        echo \Yii::$app->security->validatePassword($p, $user->password);
//        $user->password = \Yii::$app->security->generatePasswordHash($p);
//        $user->save();
//        echo \Yii::$app->security->generatePasswordHash($p);
        $date_e = date('Y-m-d H:i:s', 1564257076);
        $date = date('Y-m-d H:i:s');
//        echo $date < $date_e;
//        $time = time(1564257076);
        return json_encode($date > $date_e); //1564257076

    }

}
