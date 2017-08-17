<?php
namespace frontend\widgets\banner;

use Yii;
use yii\base\Widget;

class BannerWidget extends Widget
{
    public $items = [];

    public function init()
    {
        if(empty($this->items)){
            $this->items = [
                [
                    'label'=>'demo',
                    'image_url'=>'/statics/banner/b1.png',
                    'url'=>['site/index'],
                    'html'=>'',
                    'active'=>'active',
                ],
                [
                    'label'=>'demo',
                    'image_url'=>'/statics/banner/b2.png',
                    'url'=>['site/index'],
                    'html'=>'',
                    'active'=>'',
                ],
                [
                    'label'=>'demo',
                    'image_url'=>'/statics/banner/b3.png',
                    'url'=>['site/index'],
                    'html'=>'',
                    'active'=>'',
                ],
                [
                    'label'=>'demo',
                    'image_url'=>'/statics/banner/b4.png',
                    'url'=>['site/index'],
                    'html'=>'',
                    'active'=>'',
                ],
            ];
        }

    }

    public function run()
    {
        $data['items'] = $this->items;

        return $this->render('index', ['data'=>$data]);
    }
}