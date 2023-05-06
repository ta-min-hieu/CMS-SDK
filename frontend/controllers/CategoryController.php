<?php
namespace frontend\controllers;

use frontend\models\Category;
use frontend\models\Story;
use Yii;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\web\Controller;
use common\models\LoginForm;
use common\components\SimpleImage;
/**
 * Site controller
 */
class CategoryController extends Controller
{


    
    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionDetail($cate_id)
    {
        $category = Category::getActiveCateById($cate_id);


        if ($category) {
            $currentPage = Yii::$app->request->get('page', '1');
            $cache = Yii::$app->cache;
            $key = 'cate_detail_' . $cate_id. '_'. $currentPage;

            $data = $cache->get($key);

            if ($data === false) {
                $query = Story::getActiveStoryQuery()
                    ->innerJoinWith('categories as c', 's.id = c.story_id')
                    ->andWhere('c.id = '. $cate_id)
                    ->orderBy('s.updated_at DESC, s.id DESC');

                $countQuery = clone $query;
                // get the total number of articles (but do not fetch the article data yet)
                $count = $countQuery->count();

                // create a pagination object with the total count
                $pages = new Pagination([
                    'totalCount' => $count,
                    'defaultPageSize' => 20,
                ]);

                // limit the query using the pagination and retrieve the articles
                $listStoryPager = $query->offset($pages->offset)
                    ->limit($pages->limit)
                    ->all();

                $data = [
                    'category' => $category,
                    'listStoryPager' => $listStoryPager,
                    'pages' => $pages,
                ];

                $cache->set($key, $data);
            }

            return $this->render('detail', $data);
        } else {
            $this->redirect(Url::to(['site/error']));
        }
    }


}
