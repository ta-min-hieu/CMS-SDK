<?php
namespace frontend\widgets;
use frontend\models\ProductCategory;
use yii\base\Widget;

class CategoryBoxWidget extends Widget
{
    public $enableBoxLayout = true;
    public function run()
    {
        $cache = \Yii::$app->cache;
        $key = 'pd_cate_list';
        $data = $cache->get($key);

        if (!$data) {

            $data = ProductCategory::getActiveCategoriesByParent();
            $cache->set($key, $data, CACHE_TIMEOUT);
        }

        return $this->render('@app/views/widgets/categoryBox', [
            'cateList' => $data,
            'enableBoxLayout' => $this->enableBoxLayout,
        ]);
    }
}