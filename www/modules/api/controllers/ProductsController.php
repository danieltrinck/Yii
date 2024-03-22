<?php

namespace app\modules\api\controllers;

use yii\rest\ActiveController;
use yii\web\Controller;
use yii\data\Pagination;
use yii\data\ActiveDataProvider;
use Yii;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use app\models\Products;
use yii\filters\auth\HttpBearerAuth;
use DateTime;
use yii\web\Response;

/**
 * Default controller for the `api` module
 */
class ProductsController extends Controller
{
    public $modelClass = "app\models\Products";

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
        $filterName   = Yii::$app->request->get('filtrarProduto');
        $filterClient = Yii::$app->request->get('filtrarCliente');
        if ($filterName !== null) {
            $product = Products::find()->with('clients')->where(['like', 'nome', $filterName])->orderBy(['id' => SORT_DESC]);
        }elseif ($filterClient !== null) {
            $product = Products::find()->with([
                'clients' => function ($query) use ($filterClient) {
                    $query->where(['like', 'nome', $filterClient]);
                }
            ])->orderBy(['id' => SORT_DESC]);
        }else{
            $product = Products::find()->with('clients')->orderBy(['id' => SORT_DESC]);
        }

        // Configuração de paginação
        $dataProvider = new ActiveDataProvider([    
            'query' => $product,
            'pagination'   => [
                'pageSize' => Yii::$app->params['pagination'], // Defina o tamanho da página aqui
                'page'     => Yii::$app->request->get('page')??0
            ],
        ]);
       
       // Obtém os dados paginados
        $models     = $dataProvider->getModels();
        $totalCount = $dataProvider->getTotalCount();

        $clients = [];
        foreach ($models as $model) {

            if($filterClient && $model->clients){

                $clients[] = [
                    'product' => $model->toarray(),
                    'client'  => ($model->clients ? $model->clients->toarray() : [])
                ];

            }else{

                if(empty($filterClient)){
                    $clients[] = [
                        'product' => $model->toarray(),
                        'client'  => ($model->clients ? $model->clients->toarray() : [])
                    ];
                }
            }
        }
        // Constrói o array com os dados paginados
        $data = [
            'items' => $clients,
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
            $model = new Products();
            $model->nome        = Yii::$app->request->post('nome');
            $model->preco       = Yii::$app->request->post('preco');
            $model->clients_id  = Yii::$app->request->post('clients_id');
            $model->created_at  = $now->getTimestamp();
            $model->foto        = UploadedFile::getInstanceByName('foto');

            Yii::$app->response->statusCode = 200;
            
            if($model->validate())
            {
                if($model->foto && $model->upload()){
                    $model->foto = 'uploads/products/'.$model->foto->baseName .'.'. $model->foto->extension;
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