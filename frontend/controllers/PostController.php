<?php
namespace frontend\controllers;

use frontend\controllers\base\BaseController;
use frontend\models\PostForm;
/**
 * 文章控制器
 * @package frontend\controllers
 */
class PostController extends BaseController
{
    /**
     * 文章列表
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
