<?php
namespace frontend\controllers;

use common\models\CatsModel;
use common\models\PostModel;
use frontend\controllers\base\BaseController;
use frontend\models\PostForm;
use Yii;
/**
 * ���¿�����
 * @package frontend\controllers
 */
class PostController extends BaseController
{

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
        $cat = CatsModel::getAllCats();
        return $this->render('create', ['model' => $model, 'cat' => $cat]);
    }
}
