<?php
namespace frontend\widgets\slideshow;
use frontend\models\Advertisment;
use yii\base\Widget;

class SlideshowWidget extends Widget
{
    public $limit = 8;
    public $width = 900;
    public $height = 314;

    public function run()
    {

        $cache = \Yii::$app->cache;
        $key = 'main_slideshow_' . $this->limit;
        $data = $cache->get($key);

        if (!$data) {

            $slideshowList = Advertisment::getActiveAdvByPosition('slideshow', $this->limit);

            $data = $this->render('@app/widgets/slideshow/views/slideshow', [
                'slideshowList' => $slideshowList,
                'width' => $this->width,
                'height' => $this->height,
            ]);

            $cache->set($key, $data, CACHE_TIMEOUT);
        }

        return $data;

    }
}