<?php
namespace frontend\widgets\post;

use common\models\PostModel;
use frontend\models\PostForm;
use yii\base\Widget;
use Yii;
use yii\data\Pagination;
use yii\helpers\Url;

/**
 * 文章列表组件
 * @package frontend\widgets\post
 */
class PostWidget extends Widget
{
    /**
     * 文章列表的标题
     * @var string
     */
    public $title = '';
    /**
     * 显示条数
     * @var int
     */
    public $limit = 6;
    /**
     * 是否显示更多
     * @var bool
     */
    public $more = true;
    /**
     * 是否显示分页
     * @var bool
     */
    public $page = true;

    public function run()
    {
        $currPage = Yii::$app->request->get('page', 1);//没有的话就默认1
        //查询条件
        $condition = ['=', 'is_valid', PostModel::IS_VALID];
        $res = PostForm::getList($condition, $currPage, $this->limit);
        $result['title'] = $this->title ?:"最新文章";
        $result['more'] = Url::to(['post/index']);
        $result['body'] = $res['data'] ?: [];
        //是否分页
        if($this->page){
            $pages = new Pagination(['totalCount'=>$res['count'], 'pageSize'=>$res['pageSize']]);
            $result['page'] = $pages;
        }
        return $this->render('index', ['data'=>$result]);
    }
}