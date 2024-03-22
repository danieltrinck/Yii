<?php

namespace app\modules\api\controllers;

use yii\rest\ActiveController;
use yii\web\Controller;
use yii\data\Pagination;
use yii\data\ActiveDataProvider;
use Yii;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\filters\auth\HttpBearerAuth;
use app\models\Clients;
use DateTime;
use yii\web\Response;
/**
 * Default controller for the `api` module
 */
class ClientsController extends Controller
{
    public $modelClass = "app\models\Clients";

    public function action()
    {
        $this->enableCsrfValidation = false;
    }
  
    public function beforeAction($action) 
    { 
        $this->enableCsrfValidation = false; 
        return parent::beforeAction($action); 
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'create' => ['post'],
                ],
            ],
            'authenticator' => [
                'class' => HttpBearerAuth::className()   
            ],
            'contentNegotiator' => [
	            'class' => 'yii\filters\ContentNegotiator',
	            'formats' => [
	                'application/json' => Response::FORMAT_JSON,
	            ]
            ]
        ];
    }

    public function actionIndex()
    {
        $usr = Clients::find()->with('products')->orderBy(['id' => SORT_DESC]);

        // Configuração de paginação
        $dataProvider = new ActiveDataProvider([
            'query' => $usr,
            'pagination'   => [
                'pageSize' => Yii::$app->params['pagination'], // Defina o tamanho da página aqui
                'page'     => Yii::$app->request->get('page')??0
            ],
        ]);
       
        // Obtém os dados paginados
        $models     = $dataProvider->getModels();
        $totalCount = $dataProvider->getTotalCount();

        $products = [];
        foreach ($models as $model) {

            $modelprod = [];
            if($model->products){
                foreach ($model->products as $mp) {
                    $modelprod[] = $mp->toarray();
                }
            }
            $products[] = [
                'client'  => $model->toarray(),
                'product' => $modelprod
            ];
        }

        // Constrói o array com os dados paginados
        $data = [
            'items' => $products,
            'page'  => [

                'totalCount'  => $totalCount,
                'pageCount'   => $dataProvider->pagination->getPageCount(),
                'currentPage' => $dataProvider->pagination->getPage() +1,
                'perPage'     => $dataProvider->pagination->pageSize,
            ],
            'links' => [
                'self' => [
                    'href' => Yii::$app->request->url
                ],
                'next' => [
                    'href' => '/'.Yii::$app->request->getPathInfo().'?page='.$dataProvider->pagination->getPage() +1
                ],
                'last' => [
                    'href' => '/'.Yii::$app->request->getPathInfo().'?page='.$dataProvider->pagination->getPageCount()
                ]
            ]
        ];

        return $data;
    }

    public function actionCreate()
    {
        
        if ($this->request->isPost) 
        {
            $now = new DateTime();
            $now->format('Y-m-d H:i:s');
            $model = new Clients();
            $model->nome        = Yii::$app->request->post('nome');
            $model->cpf         = Yii::$app->request->post('cpf');
            $model->cep         = Yii::$app->request->post('cep');
            $model->logradouro  = Yii::$app->request->post('logradouro');
            $model->numero      = Yii::$app->request->post('numero');
            $model->cidade      = Yii::$app->request->post('cidade');
            $model->estado      = Yii::$app->request->post('estado');
            $model->complemento = Yii::$app->request->post('complemento');
            $model->sexo        = Yii::$app->request->post('sexo');
            $model->created_at  = $now->getTimestamp();
            $model->foto        = UploadedFile::getInstanceByName('foto');

            Yii::$app->response->statusCode = 200;
            
            if($model->validate())
            {
                if($model->foto && $model->upload()){
                    $model->foto = 'uploads/clients'.$model->foto->baseName .'.'. $model->foto->extension;
                }

                if($model->save(false))
                {
                    $model->loadDefaultValues();
                    return [
                        'success'  => true,
                        'item'     => $model,
                        'errors'   => []
                    ];
                }

            }else{

                return [
                    'success'  => false,
                    'item'     => [],
                    'errors'   => $model->errors
                ];
            }
        } 
       
    }

}