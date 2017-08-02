<?php
namespace frontend\models;

use Yii;
use yii\base\model;

/**
 * ���±�ģ��
 * @package frontend\models
 */
class PostForm extends Model
{
    public $id;
    public $title;
    public $content;
    public $lable_img;
    public $cat_id;
    public $tags;

    public $_lastError = '';

    public function rules()
    {
        return [
            [['id', 'title', 'content'], 'required'],
            [['id', 'cat_id'], 'integer'],
            ['title', 'string', 'min'=>4, 'max'=>50],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => '����',
            'title' => '����',
            'content' => '����',
            'label_img' => '��ǩͼ',
            'tags' => '��ǩ',
        ];
    }
}
