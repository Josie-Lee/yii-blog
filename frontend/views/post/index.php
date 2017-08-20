<?php

use frontend\widgets\post\PostWidget;
use yii\base\Widget;
use frontend\widgets\hot\HotWidget;
use yii\helpers\Url;
use frontend\widgets\tag\TagWidget;
?>
<div class="row">
    <div class="col-lg-9">
        <!--文章列表-->
        <?=PostWidget::widget()?>
    </div>
    <div class="col-lg-3">
        <?php if(!\Yii::$app->user->isGuest):?>
        <a class="btn btn-success btn-block btn-post" href="<?=Url::to(['post/create'])?>">写博客</a>
        <?php endif ?>
        <!--热门浏览-->
        <?=HotWidget::widget()?>
        <!--标签云-->
        <?=TagWidget::widget()?>
    </div>
</div>
