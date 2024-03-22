<?php

namespace app\controllers;

use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\data\Pagination;
use app\models\Users;
use yii\data\ActiveDataProvider;

class UsersController extends \yii\web\Controller
{
    public $modelClass = "app\models\Users";

    public function action()
    {
        $this->enableCsrfValidation = false;
    }

    public function beforeAction($action) 
    { 
        $this->enableCsrfValidation = false; 
        return parent::beforeAction($action); 
    }

    public function actionIndex()
    {
        $usr = Users::find();

        // Configuração de paginação
        $dataProvider = new ActiveDataProvider([
            'query' => $usr,
            'pagination'   => [
                'pageSize' => 1, // Defina o tamanho da página aqui
                'page'     => Yii::$app->request->get('page')??0
            ],
        ]);
       
       // Obtém os dados paginados
        $models     = $dataProvider->getModels();
        $totalCount = $dataProvider->getTotalCount();

        // Constrói o array com os dados paginados
        $data = [
            'total_count'  => $totalCount,
            'page_size'    => $dataProvider->pagination->pageSize,
            'page_count'   => $dataProvider->pagination->getPageCount(),
            'current_page' => $dataProvider->pagination->getPage() +1,
            'data'         => $models,
        ];

        return $data;
    }

    public function actionCreate()
    {
        $model = new Users();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function behaviors()
    {
        return [
            'bearerAuth' => [
                'class' => HttpBearerAuth::class,
            ],
        ];
    }
}
