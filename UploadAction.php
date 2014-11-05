<?php

namespace otsec\yii2\fileapi;

use Yii;
use yii\base\Action;
use yii\base\DynamicModel;
use yii\helpers\FileHelper;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * Class UploadAction
 *
 * @author Artem Belov <razor2909@gmail.com>
 */
class UploadAction extends Action
{
    /**
     * @var string
     */
    public $fileInputName = 'file';
    /**
     * @var string path to directory where files will be uploaded
     */
    public $path;
    /**
     * @var string validator class name
     */
    public $validator = 'yii\validators\FileValidator';
    /**
     * @var array
     */
    public $validatorOptions = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $response = ['status' => false];

        if ($file = $this->uploadedFile()) {
            $model = new DynamicModel(compact('file'));
            $model->rules($this->getValidationRules());

            if (!$model->validate()) {
                $response['errors'] = $model->getErrors();
                return $response;
            }

            if ($path = $this->saveFile($file)) {
                $response['status'] = true;
                $response['file'] = $path;
            }
        }

        return $response;
    }

    /**
     * @return UploadedFile
     */
    public function uploadedFile()
    {
        if ($files = UploadedFile::getInstancesByName($this->fileInputName)) {
            return $files[0];
        }

        return null;
    }

    /**
     * @return array
     */
    public function getValidationRules()
    {
        $rule = ['class' => $this->validator];
        $rule = array_merge($rule, $this->validatorOptions);
        return [$rule];
    }

    /**
     * @param UploadedFile $file
     *
     * @return string
     */
    public function saveFile($file)
    {
        $path = $this->normalizePath($this->path);
        $destination = $path . DIRECTORY_SEPARATOR . $file->name;

        if ($file->saveAs($destination)) {
            return $destination;
        }

        return null;
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public function normalizePath($path)
    {
        $path = Yii::getAlias($path);
        $path = FileHelper::normalizePath($path);
        $path = rtrim($path, DIRECTORY_SEPARATOR);

        return $path;
    }
}