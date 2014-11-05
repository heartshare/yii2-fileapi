<?php

namespace otsec\yii2\fileapi;

use yii\web\AssetBundle;

/**
 * Asset bundle for FileAPI jquery plugin.
 * @author Artem Belov <razor2909@gmail.com>
 */
class PluginAsset extends AssetBundle
{
    public $sourcePath = '@vendor/bower/jquery.fileapi';

    public $js = [
        'FileAPI/FileAPI.min.js',
        'jquery.fileapi.min.js'
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}