<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\modules\account\models\Profile;

class BaseController extends Controller
{
    public $user;
    public $my_profile;

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    // Only allow if user is authenticated
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function beforeAction($action)
    {
        $user = Yii::$app->user->identity;

        if ($user != null) {
            $this->user = $user;

            $this->my_profile = $this->user->profile != null ? $this->user->profile : new Profile();
        }
        else {
            $this->my_profile = new Profile();
        }

        return parent::beforeAction($action);
    }
}
