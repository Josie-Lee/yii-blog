<?php
namespace common\models\base;

use Yii;
use yii\db\ActiveRecord;

class BaseModel extends ActiveRecord
{
    public function getPages($query, $currPage = 1, $pageSize = 10, $search = null)
    {
        if ($search) {
            $query = $query->andFilerWhere($search);
        }
        $data['count'] = $query->count();
        if (!$data['count']) {
            return ['count' => 0, 'currPage' => $currPage, 'pageSize' => $pageSize, 'start' => 0, 'end' => 0,
                'data' => []
            ];
        }
        //��ǰҳ����ʵ��ҳ��
        $currPage = (ceil($data['count'] / $pageSize) < $currPage) ? ceil($data['count'] / $pageSize) : $currPage;
        //��ǰҳ
        $data['currPage'] = $currPage;
        //ÿҳ��ʾ����
        $data['pageSize'] = $pageSize;

        $data['start'] = ($currPage - 1) * $pageSize + 1;
        $data['end'] = (ceil($data['count'] / $pageSize) == $currPage)
            ? $data['count'] : $currPage * $pageSize;

        //����
        $data['data'] = $query
            ->offset(($currPage - 1) * $pageSize)
            ->limit($pageSize)
            ->asArray()
            ->all();

        return $data;
    }
}
