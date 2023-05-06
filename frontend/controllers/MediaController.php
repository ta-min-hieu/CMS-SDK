<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use common\models\PlaylistBase;
use common\models\AlbumBase;
use common\models\SongBase;
use common\models\MscCategoryBase;
use frontend\models\PlaylistForm;
use yii\data\ActiveDataProvider;

class MediaController extends Controller
{
    public function actionPlaylistDetail($id)
    {
        $playlist = $this->findPlaylistModel($id);
        $this->view->title = Yii::t('frontend', 'Playlist: {title}|umusic', ['title' => $playlist->playlist_name]);
        return $this->render('_playlist', ['playlist' => $playlist]);
    }

    protected function findPlaylistModel($id)
    {
        if (($model = PlaylistBase::find()->with(['songs', 'songs.songArtists'])->where(['id' => $id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionCreatePlaylistPopup() {
        if(!Yii::$app->request->isAjax) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $model = new PlaylistForm();
        if($model->load(Yii::$app->request->post())) {
            $model->save();
        }
        return $this->renderAjax('_playlistPopupForm', ['model' => $model]);
    }

    public function actionAlbumDetail($id)
    {
        $album = $this->findAlbumModel($id);
        $this->view->title = Yii::t('frontend', 'Album: {title}|umusic', ['title' => $album->album_name]);
        return $this->render('_album', ['album' => $album]);
    }

    protected function findAlbumModel($id)
    {
        if (($model = AlbumBase::find()->with(['songs', 'songs.songArtists'])->where(['id' => $id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionLastestSongs() {
        $this->view->title = Yii::t('frontend', 'Nhạc mới phát hành');
        $query = SongBase::find()->with(['artists'])->orderBy(['updated_at' => SORT_DESC]);
        $dataProvider = new ActiveDataProvider(['query' => $query, 'pagination' => ['pageSize' => 10]]);
        Yii::$app->getDb()->cache(function ($db) use ($dataProvider) {
            $dataProvider->prepare();
        });
        return $this->render('lastestSongs', ['dataProvider' => $dataProvider]);
    }

    public function actionBillboard() {
        $this->view->title = Yii::t('frontend', 'Bảng xếp hạng');
        $query = SongBase::find()->with(['artists'])->orderBy(['total_score' => SORT_DESC]);
        $dataProvider = new ActiveDataProvider(['query' => $query, 'pagination' => ['pageSize' => 10]]);
        Yii::$app->getDb()->cache(function ($db) use ($dataProvider) {
            $dataProvider->prepare();
        });
        return $this->render('billboard', ['dataProvider' => $dataProvider]);
    }

    public function actionCategories() {
        $this->view->title = Yii::t('frontend', 'Thể loại');
        $categories = Yii::$app->cache->getOrSet('categories', function(){
            $categories = MscCategoryBase::find()->all();
            foreach ($categories as $category) {
                $songs = \common\models\SongBase::find()->joinWith(['category c'])->where(['c.id' => $category->id])->limit(12)->all();
                $category->populateRelation('songs', $songs);
            }
            return $categories;
        });
        return $this->render('categories', ['categories' => $categories]);
    }
}