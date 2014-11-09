<?php

namespace otsec\yii2\fileapi;

use otsec\yii2\fileapi\ModelInterface as FileAPIModelInterface;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\widgets\InputWidget;

/**
 * Class FileAPI
 * @author Artem Belov <razor2909@gmail.com>
 */
class FileAPI extends InputWidget
{
    /**
     * @var Model|FileAPIModelInterface the data model that this widget is associated with.
     */
    public $model;
    /**
     * @var boolean
     */
    public $multiple = false;
    /**
     * @var string
     */
    public $template;
    /**
     * @var string
     */
    public $fileInputName = 'file';
    /**
     * @var array
     */
    public $settings = [];
    /**
     * @var mixed
     */
    public $uploadUrl;

    /**
     * @var array
     */
    protected $defaultSettings = [
        'autoUpload' => true,
        'elements' => [
            'ctrl' => [
                'upload' => '.js-fileapi-ctrl-upload',
                'reset' => '.js-fileapi-ctrl-reset',
                'abort' => '.js-fileapi-ctrl-abort',
            ],
            'empty' => [
                'show' => '.js-fileapi-empty-show',
                'hide' => '.js-fileapi-empty-hide',
            ],
            'emptyQueue' => [
                'show' => '.js-fileapi-empty-queue-show',
                'hide' => '.js-fileapi-empty-queue-hide',
            ],
            'active' => [
                'show' => '.js-fileapi-active-show',
                'hide' => '.js-fileapi-active-hide',
            ],
            'size' => '.js-fileapi-size',
            'name' => '.js-fileapi-name',
            'progress' => '.js-fileapi-progress',
            'list' => '.js-fileapi-list',
            'file' => [
                'tpl' => '.js-fileapi-file-tpl',
                'progress' => '.js-fileapi-file-progress',
                'active' => [
                    'show' => '.js-fileapi-file-active-show',
                    'hide' => '.js-fileapi-file-active-hide'
                ],
                'preview' => [
                    'el' => '.js-fileapi-file-preview',
                ],
                'abort' => '.js-fileapi-file-abort',
                'remove' => '.js-fileapi-file-remove',
                'rotate' => '.js-fileapi-file-rotate',
            ],
            'dnd' => [
                'el' => '.js-fileapi-dnd',
                'hover' => 'faw-dnd-hover',
            ],
        ],
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->multiple) {
            $this->options['id'] = false;
            $this->template = ($this->template) ?: 'multiple';
        } else {
            $this->template = ($this->template) ?: 'single';
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->registerAssetBundles();
        $this->registerSettings();
        $this->registerUploadedFiles();
        $template = $this->renderTemplate();
        $this->registerClientScripts();

        return $template;
    }

    /**
     * @return string
     */
    public function renderTemplate()
    {
        return $this->render($this->template, [
            'id' => $this->getId(),
            'attributeInputName' => Html::getInputName($this->model, $this->attribute),
            'fileInputName' => $this->fileInputName,
            'widget' => $this,
        ]);
    }

    /**
     * Register widget asset bundles
     */
    public function registerAssetBundles()
    {
        WidgetAsset::register($this->view);
    }

    /**
     * Register FileAPI jquery library settings
     */
    public function registerSettings()
    {
        $this->settings = ArrayHelper::merge($this->defaultSettings, $this->settings, [
            'multiple' => $this->multiple,
            'url' => Url::to($this->uploadUrl, true),
        ]);

        // Если CSRF защита включена, добавляем токен в запросы виджета.
        $request = Yii::$app->request;
        if ($request->enableCsrfValidation) {
            $this->settings['data'][$request->csrfParam] = $request->getCsrfToken();
        }
    }

    /**
     * Register already saved files.
     */
    public function registerUploadedFiles()
    {
        if ($this->model instanceof FileAPIModelInterface) {
            if ($files = $this->model->getFileAPIUploadedFiles($this->attribute)) {
                $this->settings['files'] = $files;
            }
        }
    }

    /**
     * Register FileAPI jquery library on file input.
     */
    public function registerClientScripts()
    {
        $selector = '#' . $this->getId();
        $settings = Json::encode($this->settings);

        $this->view->registerJs("jQuery('{$selector}').fileapi({$settings});");
    }
}