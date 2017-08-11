<?php
namespace frontend\models;

use common\models\PostModel;
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

    const SCENARIOS_CREATE = 'create';
    const SCENARIOS_UPDATE = 'update';

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
            $model->created_at = time();
            $model->updated_at = time();
            if(!$model->save()){
                throw new \Exception('文章保存失败');
            }
            $this->id = $model->id;


            //调用事件
            $model->_eventAfterCreate();



            $transaction->commit();
            return true;

        }catch(\Exception $e){
            $transaction->rollBack();
            $this->_lastError = $e->getMessage();
            return false;
        }
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










}
