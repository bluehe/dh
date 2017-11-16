<?php

namespace dh\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use dh\models\Suggest;

/**
 * Person controller
 */
class PersonController extends Controller {

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex() {
        $suggest = new ActiveDataProvider([
            'query' => Suggest::find(),
            'pagination' => ['pageSize' => 5],
            'sort' => ['defaultOrder' => [
                    'id' => SORT_DESC,
                ]],
        ]);
        return $this->render('index', [
                    'suggest' => $suggest,
        ]);
    }

    /**
     * Lists all Suggest models.
     * @return mixed
     */
    public function actionSuggest() {
        $dataProvider = new ActiveDataProvider([
            'query' => Suggest::find(),
            'sort' => ['defaultOrder' => [
                    'id' => SORT_DESC,
                ]],
        ]);

        return $this->render('suggest', [
                    'dataProvider' => $dataProvider,
        ]);
    }

}
