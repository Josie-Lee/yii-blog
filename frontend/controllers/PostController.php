<?php
namespace frontend\controllers;

use frontend\controllers\base\BaseController;
use frontend\models\PostForm;
/**
 * ���¿�����
 * @package frontend\controllers
 */
class PostController extends BaseController
{
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
        return $this->render('create', ['model' => $model]);
    }
}
