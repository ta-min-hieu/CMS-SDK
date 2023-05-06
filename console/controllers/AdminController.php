<?php

namespace console\controllers;

use common\models\VtAlbumBase;
use common\models\VtSongBase;
use common\models\VtVideoBase;
use yii\console\Controller;

class AdminController extends Controller {

    public function actionSinger($type = 1, $time = 30) {
        ini_set('memory_limit', '-1');
        date_default_timezone_set('Asia/Saigon');
        $date = date('Y-m-d H:i:s', strtotime("-$time minutes", time()));
        $type = ($type) ? intval($type) : 1;

        echo "param: $type" . "\n";
        echo "param: $date" . "\n";

        sleep(3);

        if ($type == 1) {
            $data = VtSongBase::find()
                    ->select('vt_song.*')
                    ->innerJoin('vt_song_singer_join', 'vt_song_singer_join.song_id = vt_song.id')
                    ->innerJoin('vt_singer', 'vt_song_singer_join.singer_id = vt_singer.id')
                    ->where('vt_singer.updated_at >= :date', [':date' => $date])
                    ->all();
        }
        if ($type == 2) {
            $data = VtVideoBase::find()
                    ->select('vt_video.*')
                    ->innerJoin('vt_video_singer_join', 'vt_video_singer_join.video_id = vt_video.id')
                    ->innerJoin('vt_singer', 'vt_video_singer_join.singer_id = vt_singer.id')
                    ->where('vt_singer.updated_at >= :date', [':date' => $date])
                    ->all();
        }
        if ($type == 3) {
            $data = VtAlbumBase::find()
                    ->select('vt_album.*')
//                    ->innerJoin('vt_album_singer_join', 'vt_album_singer_join.album_id = vt_album.id')
//                    ->innerJoin('vt_singer', 'vt_album_singer_join.singer_id = vt_singer.id')
//                    ->where('vt_singer.updated_at >= :date', [':date' => $date])
                    ->where('vt_album.is_active = 1')
                    ->all();
        }
         echo "Numb data: ".count($data);
        if ($data) {
            foreach ($data as $item) {
                $singerData = [];
                switch ($type) {
                    case 1:
                        $singers = \common\models\VtSingerBase::find()
                                ->innerJoin('vt_song_singer_join', 'vt_song_singer_join.singer_id = vt_singer.id')
                                ->where(['vt_song_singer_join.song_id' => $item->id])
                                ->orderBy('vt_song_singer_join.priority')
                                ->all();
                        break;
                    case 2:
                        $singers = \common\models\VtSingerBase::find()
                                ->innerJoin('vt_video_singer_join', 'vt_video_singer_join.singer_id = vt_singer.id')
                                ->where(['vt_video_singer_join.video_id' => $item->id])
                                ->orderBy('vt_video_singer_join.priority')
                                ->all();
                        break;
                    case 3:
                        $singers = \common\models\VtSingerBase::find()
                                ->innerJoin('vt_album_singer_join', 'vt_album_singer_join.singer_id = vt_singer.id')
                                ->where(['vt_album_singer_join.album_id' => $item->id])
                                ->orderBy('vt_album_singer_join.priority')
                                ->all();
                        break;
                }
                echo "Numb singer: ".count($singers);
                if(count($singers) > 0){
                    foreach ($singers as $singer) {
                        if ($singer->is_active == 1) {
                            $temp['id'] = $singer->id;
                            $temp['alias'] = $singer->alias;
                            $temp['slug'] = $singer->slug;
                            $temp['image_path'] = $singer->image_path;
                            $temp['image_blur_path'] = $singer->image_blur_path;
                            $singerData[] = $temp;
                        }
                    }
                    if ($type == 3) {
                        $item->singers = json_encode($singerData);
                    } else {
                        $item->singer_list = json_encode($singerData);
                    }
                    $item->save(false);
                    echo "\n Updated for " . $item->id . '->' . \yii\helpers\Html::encode($item->slug);
                }

            }
        }
        echo "\n Done syn at " . date('Y-m-d H:i:s');
    }

    public function actionSong() {
        $offset = 0;
        $limit = 1000;
        $songModel = new VtSongBase();
        $allRecord = $songModel->find()->count();
        while ($offset < $allRecord) {
            $songs = VtSongBase::find()
                    ->where(['is_active' => 1])
                    ->offset($offset)
                    ->limit($limit)
                    ->all();
            foreach ($songs as $item) {
                $singers = $item->singers;
                $singerList = [];
                if ($singers) {
                    foreach ($singers as $singer) {
                        $temp['id'] = $singer->id;
                        $temp['name'] = $singer->name;
                        $temp['alias'] = $singer->alias;
                        $temp['slug'] = $singer->slug;
                        $temp['image_path'] = $singer->image_path;
                        $temp['image_blur_path'] = $singer->image_blur_path;
                        $singerList[] = $temp;
                    }
                }
                $item->singer_list = json_encode($singerList);
                $item->save(false);
            }
            $offset+= $limit;
            echo "\n offset: $offset Syn Song singer_list done: " . date('Y-m-d H:i:s');
        }
    }

    public function actionVideo() {
        $offset = 0;
        $limit = 1000;
        $songModel = new VtVideoBase();
        $allRecord = $songModel->find()->count();
        while ($offset < $allRecord) {
            $songs = VtVideoBase::find()
                    ->offset($offset)
                    ->limit($limit)
                    ->all();
            foreach ($songs as $item) {
                $singers = $item->singers;
                $singerList = [];
                if ($singers) {
                    foreach ($singers as $singer) {
                        $temp['id'] = $singer->id;
                        $temp['name'] = $singer->name;
                        $temp['alias'] = $singer->alias;
                        $temp['slug'] = $singer->slug;
                        $temp['image_path'] = $singer->image_path;
                        $temp['image_blur_path'] = $singer->image_blur_path;
                        $singerList[] = $temp;
                    }
                }
                $item->singer_list = json_encode($singerList);
                $item->save(false);
            }
            $offset+= $limit;
            echo "\n offset: $offset Syn Video singer_list done: " . date('Y-m-d H:i:s');
        }
    }

    /**
     *
     */
    public function actionBlur() {
        $model = new \common\models\VtSingerBase();
        $allRecord = $model->find()->where('image_blur_path is null or image_blur_path = \'\'')->count();
        echo "number singer: " . $allRecord . "\n";die;
        $limit = 1000;
        $offset = 0;
        $count = 0;
        while ($offset < $allRecord) {
            echo "Offset: " . $offset . "\n";
            $songs = \common\models\VtSingerBase::find()
                    ->where('image_blur_path is null')
                    ->orderBy('id')
                    ->offset($offset)
                    ->limit($limit)
                    ->all();
            foreach ($songs as $item) {
                echo $count++ . "  |  id: " . $item->id . "  file path: " . $item->image_path . "\n";
                $blurPath = \common\libs\Images::BlurImage($item->image_path);
                echo $blurPath . "\n";
                $item->image_blur_path = $blurPath;
                if ($item->save(false)) {
                    echo "success \n";
                } else {
                    echo "update fail \n";
                }
            }
            $offset+= $limit;
        }
    }

    public function actionVideoblur() {
        $model = new \common\models\VtVideoBase();
        $allRecord = $model->find()->count();
        $limit = 1000;
        $offset = 0;
        while ($offset < $allRecord) {
            $songs = \common\models\VtVideoBase::find()
                    ->where('image_blur_path is null')
                    ->offset($offset)
                    ->limit($limit)
                    ->all();
            foreach ($songs as $item) {
                echo "id: " . $item->id . "  file path: " . $item->image_path . "\n";
                $imagePath = $item->image_path;
                $blurPath = \backend\components\common\Images::BlurImage($imagePath);
                echo $blurPath . "\n";
                $item->image_blur_path = $blurPath;
                if ($item->save(false)) {
                    echo "success \n";
                } else {
                    echo "update fail \n";
                }
            }
            $offset+= $limit;
        }
    }

    public function actionWord() {
        $model = new \common\models\VtSingerBase();
        $allRecord = $model->find()->count();
        echo "number singer: " . $allRecord . "\n";
        $limit = 1000;
        $offset = 0;
        $count = 0;
        while ($offset < $allRecord) {
            $data = \common\models\VtSingerBase::find()
                    ->orderBy('id')
                    ->offset($offset)
                    ->limit($limit)
                    ->all();
            foreach ($data as $item) {
                $item->first_word = \common\helpers\Helpers::getFirstWordSingerByAlias($item->alias);
                $item->save(false);
                echo $item->id . ' - ' . $item->alias . ' - ' . $item->first_word . "\n";
            }
            $offset+= $limit;
        }
        echo "\n Generate first word for singer done! " . date('Y-m-d H:i:s');
    }

    public function actionVideoThumb() {
        $offset = 0;
        $limit = 1000;
        $model = new VtVideoBase();
        $allRecord = $model->find()->count();
        while ($offset < $allRecord) {
            $items = VtVideoBase::find()
                    ->offset($offset)
                    ->limit($limit)
                    ->all();
            foreach ($items as $item) {
                $item->createThumbImages($item->image_path, $item->id);
            }
            $offset+= $limit;
            echo "\n offset: $offset Syn Video singer_list done: " . date('Y-m-d H:i:s');
        }
    }

}
