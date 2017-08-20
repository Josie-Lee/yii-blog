<?php
namespace frontend\models;

use Yii;
use common\models\FeedsModel;
use yii\base\Model;
class FeedForm extends Model
{
    public $content;

    public $_lastError;

    public function rules()
    {
        return [
            ['content', 'required'],
            ['content', 'string', 'max'=>255]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content' => '内容'
        ];
    }


    public function getList()
    {
        $model = new FeedsModel();
        $res = $model->find()
            ->limit(10)
            ->with('user')
            ->orderBy(['id'=>SORT_DESC])
            ->asArray()
            ->all();
        return $res?:[];
    }


    /**
     * 创建留言
     */
    public function create()
    {
        try{
            $model = new FeedsModel();
            $model->user_id = Yii::$app->user->identity->id;
            $model->content = $this->content;
            $model->created_at = time();
            if(!$model->save()){
                throw new \Exception('保存失败');
            }
            return true;
        }catch (\Exception $e){
            $this->_lastError = $e->getMessage();
            return false;
        }
    }
}
