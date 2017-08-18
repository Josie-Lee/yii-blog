<?php
namespace frontend\widgets\chat;

use frontend\models\FeedForm;
use yii\bootstrap\Widget;
use Yii;
/**
 * ÁôÑÔ°å×é¼ş
 */

class ChatWidget extends Widget
{
    public function run()
    {
        $feed = new FeedForm();
        $data['feed'] = $feed->getList();
        return $this->render('index', ['data'=>$data]);

    }
}