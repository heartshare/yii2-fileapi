<?php

use yii\helpers\Html;
use yii\web\JsExpression;

/**
 * @var $widget otsec\yii2\fileapi\FileAPI
 * @var $this   yii\web\View
 *
 * @var $id                 string widget ID selector
 * @var $attributeInputName string field attribute name
 * @var $fileInputName      string the parameter name for the file form data
 *
 * FileAPI item template variables:
 *  - uid
 *  - name (string)
 *  - type (string)
 *  - size (integer)
 *  - sizeText (stirng)
 *  - complete (boolean)
 */
?>

<div class="faw-container faw-container-multiple" id="<?= $id ?>">
    <?= Html::hiddenInput($attributeInputName, '', $widget->options) ?>

    <div class="faw-group faw-group-preview js-fileapi-list">
        <a href="#" class="faw-preview js-fileapi-file-tpl">
        <span class="faw-preview-delete js-fileapi-file-delete">
            <span class="faw-preview-delete-icon glyphicon glyphicon-trash"></span>
        </span>
            <span class="faw-canvas-container js-fileapi-file-preview"></span>
            <?= Html::hiddenInput($attributeInputName . '[]', '<%= name %>', $widget->options) ?>
        </a>
    </div>

    <div class="faw-group faw-group-progress js-fileapi-active-show">
        <div class="progress progress-striped">
            <div class="progress-bar progress-bar-info active js-fileapi-progress"
                 role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
    </div>

    <div class="faw-group faw-group-upload-button js-fileapi-dnd">
        <div class="faw-upload-button btn btn-default js-fileapi-active-hide">
        <span class="faw-upload-button-default-text">
            <span class="glyphicon glyphicon-picture"></span>
            Добавить изображения
        </span>

        <span class="faw-upload-button-dnd-text">
            <span class="glyphicon glyphicon-arrow-down"></span>
            Отпустите для начала загрузки
        </span>

            <?= Html::fileInput($fileInputName) ?>
        </div>
    </div>
</div>

<?php

$widget->settings['onFileComplete'] = new JsExpression('function (evt, uiEvt) {
    if (uiEvt.result.error) {
        alert(uiEvt.result.error);
    } else {
        var uid = FileAPI.uid(uiEvt.file);
        jQuery(this).find("[data-fileapi-id=\'" + uid + "\']").find("input[type=\'hidden\']").val(uiEvt.result.file);
    }
}');

$this->registerJs('
(function($) {
    var $container = $("#'.$id.'");

    $container.on("click", ".js-fileapi-file-delete", function(event) {
        event.preventDefault();
        var fileId = $(this).parents("[data-fileapi-id]").data("fileapi-id");
        $container.fileapi("remove", fileId);
    });
})(jQuery);');