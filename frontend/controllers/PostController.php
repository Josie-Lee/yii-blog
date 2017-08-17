<?php
namespace frontend\controllers;

use common\models\CatsModel;
use common\models\PostExtendsModel;
use frontend\controllers\base\BaseController;
use frontend\models\PostForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
/**
 * ���¿�����
 * @package frontend\controllers
 */
class PostController extends BaseController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'create'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        //'roles' => ['?'],����¼�ܷ��ʣ�������Ҫ��¼����ܷ��ʣ�����ע�͵�
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    '*' => ['get', 'post'],
                    'create' => ['get', 'post'],//������ʡ��
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'upload'=>[
                'class' => 'common\widgets\file_upload\UploadAction',     //������չ��ַ��д��
                'config' => [
                    'imagePathFormat' => "/image/{yyyy}{mm}{dd}/{time}{rand:6}",
                ]
            ],
            'ueditor'=>[
                'class' => 'common\widgets\ueditor\UeditorAction',
                'config'=>[
                    //�ϴ�ͼƬ����
                    'imageUrlPrefix' => "", /* ͼƬ����·��ǰ׺ */
                    'imagePathFormat' => "/image/{yyyy}{mm}{dd}/{time}{rand:6}", /* �ϴ�����·��,�����Զ��屣��·�����ļ�����ʽ */
                ]
            ],
        ];
    }

    /**
     * �����б�
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }


    public function actionCreate()
    {
        $model = new PostForm();
        $model->setScenario(PostForm::SCENARIOS_CREATE);
        if($model->load(Yii::$app->request->post()) && $model->validate()){
            if(!$model->create()){
                Yii::$app->session->setFlash('warning', $model->_lastError);
            }else{
                return $this->redirect(['post/view', 'id'=>$model->id]);
            }
        }

        $cat = CatsModel::getAllCats();
        return $this->render('create', ['model' => $model, 'cat' => $cat]);
    }

    /**
     * ��������
     */
    public function actionView($id)
    {
        $model = new PostForm();
        $data = $model->getViewById($id);
        $time = date('Y-m-d', $data['created_at']);
        $data['created_at'] = $time;

        //����ͳ��
        $model = new PostExtendsModel();
        $model->upCounter(['post_id'=>$id], 'browser', 1);
        return $this->render('view', ['data'=>$data]);
    }
}
