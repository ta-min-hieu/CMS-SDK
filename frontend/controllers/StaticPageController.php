<?php

namespace frontend\controllers;
use frontend\models\Post;
use frontend\models\StaticPage;
use Yii;
use yii\helpers\Url;

class StaticPageController extends FrontendController
{
    public function actionIndex($page_slug)
    {
        $this->layout = 'postLayout';
        if ($page_slug) {
            $cache = Yii::$app->cache;
            $key = 'page_detail_'. md5($page_slug);

            $data = $cache->get($key);

            if (!$data) {
                $data = StaticPage::find()
                    ->where([
                        'is_active' => 1,
                        'slug' => $page_slug,
                    ])
                    ->one();

                // store $data in cache so that it can be retrieved next time
                $cache->set($key, $data, CACHE_TIMEOUT);
            }

            if ($data) {
                return $this->render('index', [
                    'page' => $data,
                ]);
            } else {
                $this->redirect(Url::to(['site/error']));
            }
        } else {
            $this->redirect(Url::to(['site/error']));
        }
    }



}
