<?php
namespace frontend\controllers;

use frontend\models\Author;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use common\models\LoginForm;
use common\components\SimpleImage;
/**
 * Site controller
 */
class AuthorController extends Controller
{


    
    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionDetail($authorSlug)
    {

        $author = Author::find()
            ->andWhere('slug = :slug', [':slug' => $authorSlug])
            ->one();

        if ($author) {
            return $this->render('detail', [
                'author' => $author,
            ]);
        } else {
            $this->redirect(Url::to('site/error'));
        }
    }


}
