<?php

namespace otsec\yii2\fileapi;

use yii\web\AssetBundle;

/**
 * Asset bundle for FileAPI Yii2 input widget.
 * @author Artem Belov <razor2909@gmail.com>
 */
class WidgetAsset extends AssetBundle
{
    public $sourcePath = '@otsec/yii2-fileapi/assets';

    public $css = [
        'yii2-fileapi.css',
    ];

    public $depends = [
        'otsec\yii2\fileapi\PluginAsset',
        'yii\web\YiiAsset',
    ];
}