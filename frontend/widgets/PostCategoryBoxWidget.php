<?php
namespace frontend\widgets;
use frontend\models\PostCategory;
use yii\base\Widget;

class PostCategoryBoxWidget extends Widget
{
    public $boxId = 'post-cate-box';
    public function run()
    {
        $cache = \Yii::$app->cache;
        $key = 'post_cate_list';
        $data = $cache->get($key);

        if (!$data) {
            $data = PostCategory::getActiveCategoriesByParent();
            $cache->set($key, $data, CACHE_TIMEOUT);
        }

        return $this->render('@app/views/widgets/postCategoryBox', [
            'cateList' => $data,
            'boxId' => $this->boxId,
        ]);
    }
}