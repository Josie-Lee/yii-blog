<?php

use yii\base\Widget;
use frontend\widgets\banner\BannerWidget;
use frontend\widgets\post\PostWidget;
//use frontend\widgets\chat\ChatWidget;

$this->title = '博客';
?>

<div class="row">
    <div class="col-lg-9">
        <!--图片轮播-->
        <?=BannerWidget::widget()?>
    </div>
    <div class="col-lg-3">
        2222
    </div>
    <div class="row">
        <div class="col-lg-9">
            <!--文章列表啊-->
            <?=PostWidget::widget()?>
        </div>
        <div class="col-lg-3">

        </div>
    </div>
</div>