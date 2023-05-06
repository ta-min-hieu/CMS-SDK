<?php
namespace frontend\widgets\hotPost;
use frontend\models\Post;
use yii\base\Widget;

class HotPostWidget extends Widget
{
    public $limit = 3;
    public function run()
    {

        $cache = \Yii::$app->cache;
        $key = 'hot_post_' . $this->limit;
        $data = $cache->get($key);

        if (!$data) {

            $postList = Post::getActivatedPostQuery()
                ->andWhere(['is_hot' => 1])
                ->limit($this->limit)
                ->all();

            $data = $this->render('@app/widgets/hotPost/views/hotPost', [
                'postList' => $postList,

            ]);

            $cache->set($key, $data, CACHE_TIMEOUT);
        }

        return $data;
    }
}