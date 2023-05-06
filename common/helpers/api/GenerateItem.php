<?php
/**
 * Created by PhpStorm.
 *
 * Date: 10/22/2016
 * Time: 8:49 AM
 */

namespace common\helpers\api;


use common\models\CsmAttributeBase;
use common\models\CsmCrawlerBase;
use common\models\CsmItemDeletedBase;
use common\models\CsmMediaBase;

class GenerateItem
{
    /**
     * Generate media to json object
     * @param CsmMediaBase $media
     * @param array $typeMetadata
     * @return array
     */
    public static function generateMedia($media, $typeMetadata)
    {
        $attributes = json_decode($media->attributes, true);
        $resAttr = [];
        if ($typeMetadata && count($typeMetadata)) {
            foreach ($attributes as $attribute) {
                if (in_array($attribute['type'], $typeMetadata))
                    $resAttr[] = $attribute;
            }
        }
        return [
            'id' => $media->id,
            'name' => $media->name,
            'slug' => $media->slug,
            'short_desc' => $media->short_desc,
            'description' => $media->description,
            'price_download' => $media->price_download,
            'price_play' => $media->price_play,
            'type' => $media->type,
            'max_quantity' => $media->max_quantity,
            'attributes' => $resAttr,
            'created_at' => $media->created_at,
            'updated_at' => $media->updated_at,
            'published_at' => $media->published_at,
            'duration' => $media->duration,
            'resolution' => $media->resolution,
            'cp_id' => $media->cp_id,
            'cp_info' => $media->cp_info,
            'original_path' => $media->original_path,
            'image_path' => $media->image_path,
            'file_type' => $media->file_type,
            'convert_path' => $media->convert_path,
            'meta_info' => $media->meta_info,
            'tag' => $media->tag,
            'seo_title' => $media->seo_title,
            'seo_description' => $media->seo_description,
            'seo_keywords' => $media->seo_keywords,
            'is_crawler' => $media->is_crawler,
            'crawler_id' => intval($media->crawler_id)
        ];
    }

    /**
     * Generate attribute type to json object
     * @param CsmAttributeBase $attribute
     * @return array
     */
    public static function generateAttributeType($attribute)
    {
        return [
            'id' => $attribute->id,
            'name' => $attribute->name,
            'slug' => $attribute->slug,
            'created_at' => $attribute->created_at,
            'updated_at' => $attribute->updated_at,
        ];
    }

    /**
     * Generate item deleted to json object
     * @param CsmItemDeletedBase $itemDeleted
     * @return array
     */
    public static function generateItemDeleted($itemDeleted)
    {
        return [
            'type' => $itemDeleted->type,
            'item_id' => $itemDeleted->item_id,
            'item_data' => $itemDeleted->item_data,
            'deleted_at' => $itemDeleted->deleted_at,
        ];
    }

    /**
     * Generate item attribute to json object
     * @param CsmAttributeBase $attribute
     * @return array
     */
    public static function generateItemAttribute($attribute)
    {
        return [
            'id' => $attribute->id,
            'name' => $attribute->name,
            'slug' => $attribute->slug,
            'description' => $attribute->description,
            'image_path' => $attribute->image_path,
            'second_image' => $attribute->second_image,
            'type' => $attribute->type,
            'created_at' => $attribute->created_at,
            'updated_at' => $attribute->updated_at,
            'media_list' => $attribute->media_list,
        ];
    }

    /**
     * Generate item attribute to json object
     * @param CsmCrawlerBase $crawler
     * @return array
     */
    public static function generateCrawler($crawler)
    {
        return [
            'id' => $crawler->id,
            'name' => $crawler->name,
            'url' => $crawler->url,
            'params' => $crawler->params,
            'type' => $crawler->type,
            'is_authenticated' => $crawler->is_authenticated,
            'state' => $crawler->state,
            'username' => $crawler->username,
            'password' => $crawler->password,
            'token' => $crawler->token,
            'controller_id' => $crawler->controller_id,
            'controller_info' => $crawler->controller_info,
            'level_crawler' => $crawler->level_crawler,
            'start_time' => $crawler->start_time,
            'end_time' => $crawler->end_time,
            'is_actived' => $crawler->is_actived,
            'is_need_approved' => $crawler->is_need_approved,
            'auto_client' => $crawler->auto_client,
            'max_items' => $crawler->max_items,
            'allow_null_date' => $crawler->allow_null_date,
            'min_date' => $crawler->min_date,
            'max_date' => $crawler->max_date,
            'parent_id' => $crawler->parent_id,
            'schedule_type' => $crawler->schedule_type,
            'cron_syntax' => $crawler->cron_syntax,
        ];
    }
}