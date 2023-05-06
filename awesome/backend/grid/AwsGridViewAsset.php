<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace awesome\backend\grid;
use awesome\backend\assets\AssetBundle;

/**
 * This asset bundle provides the javascript files for the [[GridView]] widget.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AwsGridViewAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->depends = array_merge($this->depends, [
            'yii\grid\GridViewAsset'
        ]);
        $this->setSourcePath(__DIR__ . '/assets');
        $this->setupAssets('js', [
            'js/jquery.float-thead',
            'js/aws-datatable'
        ]);
//        $this->setupAssets('css', ['css/datatables.bootstrap']);
//        $this->setupAssets('js', []);
        parent::init();
    }
}
