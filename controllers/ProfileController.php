<?php

namespace app\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use app\models\Profile;

class ProfileController extends BaseController
{
    /**
     * Displays profile page
     *
     * @return string
     */
    public function actionIndex($username, $this->my_profile)
    {
        $profile = $this->findByUsername($username);

        return $this->render('index', [
            'profile' => $profile,
        ]);
    }

    /**
     * Find profile by username
     *
     * @param string $username
     *
     * @return Profile $profile
     * @throws NotFoundHttpException
     */
    protected function findByUsername($username)
    {
        $my_username = $this->my_profile->username;

        // if you are viewing your own profile
        if (strcasecmp($username, $my_username) == 0) {
            return $this->my_profile;
        }
        elseif ($profile = Profile::findByUsername($username)) !== null) {
            return $profile;
        }
        else {
            throw new NotFoundHttpException('The requested profile does not exist.');
        }
    }
}
