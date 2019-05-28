<?php

namespace app\controllers;

use Yii;
use app\models\ProfileSearch;

class SearchController extends BaseController
{
    /**
     * Displays search page
     *
     * @return string
     */
    public function actionIndex()
    {
        // Set up the search model
        $searchModel = new ProfileSearch($this->profile);

        // Perform the searhc and get the dataProfile instance
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // Ajax request (search) will render only the results container
        if (Yii::$app->request->isAjax) {
            return $this->renderPartial('_results', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
