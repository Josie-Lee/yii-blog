<?php
namespace frontend\models;

use common\models\PostModel;
use common\models\RelationPostTagsModel;
use Yii;
use yii\base\Exception;
use yii\base\Model;

/**
 * 文章表单模型
 * @package frontend\models
 */
class PostForm extends Model
{
    public $id;
    public $title;
    public $content;
    public $label_img;
    public $cat_id;
    public $tags;

    public $_lastError = '';

    /**
     * 定义场景
     */
    const SCENARIOS_CREATE = 'create';
    const SCENARIOS_UPDATE = 'update';
    /**
     * 定义事件
     * eventAfterCreate 创建之后的事件
     * eventAfterUpdate 更新之后的事件
     */
    const EVENT_AFTER_CREATE = 'eventAfterCreate';
    const EVENT_AFTER_UPDATE = 'eventAfterUpdate';



    /**
     * 场景设置
     */
    public function scenarios()
    {
        $scenarios = [
            self::SCENARIOS_CREATE => ['title', 'content', 'label_img', 'cat_id', 'tags'],
            self::SCENARIOS_UPDATE => ['title', 'content', 'label_img', 'cat_id', 'tags'],
        ];
        return array_merge(parent::scenarios(), $scenarios);
    }

    public function rules()
    {
        return [
            [['title', 'content'], 'required'],
            [['cat_id'], 'integer'],
            ['title', 'string', 'min'=>4, 'max'=>50],
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => '标题',
            'content' => '内容',
            'label_img' => '标签图',
            'tags' => '标签',
            'cat_id' => '分类',
        ];
    }


    public function create()
    {
        //事务的使用
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $model = new PostModel();
            $model->setAttributes($this->attributes);
            $model->summary = $this->_getSummary();
            $model->user_id = Yii::$app->user->identity->id;
            $model->user_name = Yii::$app->user->identity->username;
            $model->is_valid = PostModel::IS_VALID;
            $model->created_at = time();
            $model->updated_at = time();
            if(!$model->save()){
                throw new \Exception('文章保存失败');
            }
            $this->id = $model->id;
            //调用事件
            $data = array_merge($this->getAttributes(), $model->getAttributes());
            $this->_eventAfterCreate($data);
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollBack();
            $this->_lastError = $e->getMessage();
            return false;
        }
    }

    public static function getList($cond, $currPage = 1, $pageSize = 5, $orderBy = ['id'=>SORT_DESC])
    {
        $model = new PostModel();
        //查询语句
        $select = ['id', 'title', 'summary', 'label_img', 'cat_id', 'user_id', 'user_name', 'is_valid',
            'created_at', 'updated_at'
        ];
        $query = $model->find()
            ->select($select)
            ->where($cond)
            ->with('relate.tag', 'extend')
            ->orderBy($orderBy);
        //获取分页数据
        $res = $model->getPages($query, $currPage, $pageSize);
        //格式化
        $res['data'] = self::_formatList($res['data']);
        return $res;
    }

    /**
     * 格式化数据
     * @param $data
     * @return mixed
     */
    public static function _formatList($data)
    {
        foreach($data as &$list){
            $list['tags'] = [];
            if(isset($list['relate']) && !empty($list['relate'])){
                foreach($list['relate'] as $val){
                    $list['tags'][] = $val['tag']['tag_name'];
                }
            }
            unset($list['relate']);
        }
        return $data;
    }

    public function getViewById($id)
    {
        $res = PostModel::find()->with('relate.tag', 'extend')->where(['id'=>$id])->asArray()->one();
        if(!$res){
            throw new \Exception('文章不存在');
        }
        //处理标签格式
        $res['tags'] = [];
        if(!empty(@$res['relate'])){//也可以if(isset($res['relate']) && !empty($res['relate']))
            foreach($res['relate'] as $list){
                $res['tags'][] = $list['tag']['tag_name'];
            }
        }
        return $res;
    }

    /**
     * 截取文章摘要
     * @param int $start
     * @param int $end
     * @param string $char
     * @return null|string
     */
    private function _getSummary($start = 0, $end = 90, $char = 'utf-8')
    {
        if(empty($this->content)){
            return null;
        }
        return (mb_substr(str_replace('&nbsp;', '', strip_tags($this->content)), $start, $end, $char));
    }


    /**
     * 创建完成后调用事件
     */
    public function _eventAfterCreate($data)
    {
        //添加事件
        $this->on(self::EVENT_AFTER_CREATE, [$this, '_eventAddTag'], $data);  //取消就是off
        //触发事件
        $this->trigger(self::EVENT_AFTER_CREATE);
    }

    /**
     * 添加标签
     */
    public function _eventAddTag($event)
    {
        //保存标签
        $tag = new TagForm();
        $tag->tags = $event->data['tags'];
        $tagIds = $tag->saveTags();

        //删除原来的关系
        RelationPostTagsModel::deleteAll(['post_id'=>$event->data['id']]);

        //批量保存文章和标签的关联关系
        if(!empty($tagIds)){
            foreach($tagIds as $k=>$id){
                $row[$k]['post_id'] = $this->id;
                $row[$k]['tag_id'] = $id;
            }
            $model = new RelationPostTagsModel();
            $res = $model->BatchInsert($row);
            if(!$res){
                throw new \Exception('关联关系保存失败');
            }
        }
    }
}
