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
    protected $defaultSettingsSingle = [
        'autoUpload' => true,
        'elements' => [
            'preview' => [
                'el' => '[data-fileapi="preview"]',
            ],
            'dnd' => [
                'hover' => 'faw-dnd-hover',
            ],
        ],
    ];
    /**
     * @var array
     */
    protected $defaultSettingsMultiple = [
        'autoUpload' => true,
        'elements' => [
            'file' => [
                'preview' => [
                    'el' => '[data-fileapi="file.preview"]',
                ],
            ],
            'dnd' => [
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
            $this->settings = ArrayHelper::merge($this->defaultSettingsMultiple, $this->settings);
            $this->template = ($this->template) ?: 'multiple';
        } else {
            $this->settings = ArrayHelper::merge($this->defaultSettingsSingle, $this->settings);
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
        $defaultSettings = ($this->multiple)
            ? $this->defaultSettingsMultiple
            : $this->defaultSettingsSingle;

        $this->settings = ArrayHelper::merge($defaultSettings, $this->settings, [
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