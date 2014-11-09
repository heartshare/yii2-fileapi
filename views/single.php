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

<div class="faw-container faw-container-single" id="<?= $id; ?>">
    <div class="faw-group faw-group-preview" data-fileapi="empty.hide">
        <a href="#" class="faw-preview">
            <span class="faw-preview-delete" data-fileapi="delete">
                <span class="faw-preview-delete-icon glyphicon glyphicon-trash"></span>
            </span>
            <span class="faw-canvas-container" data-fileapi="preview"></span>
        </a>
    </div>

    <div class="faw-group faw-group-progress" data-fileapi="active.show">
        <div class="progress progress-striped">
            <div class="progress-bar progress-bar-info active" data-fileapi="progress"
                 role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
    </div>

    <div class="faw-group faw-group-upload-button" data-fileapi="dnd">
        <div class="faw-upload-button btn btn-default" data-fileapi="active.hide">
            <span class="faw-upload-button-default-text">
                <span class="glyphicon glyphicon-picture"></span>
                Добавить изображение
            </span>

            <span class="faw-upload-button-dnd-text">
                <span class="glyphicon glyphicon-arrow-down"></span>
                Отпустите для начала загрузки
            </span>

            <?= Html::fileInput($fileInputName) ?>
            <?= Html::hiddenInput($attributeInputName, '<%= name %>', $widget->options) ?>
        </div>
    </div>
</div>

<?php

$widget->settings['onFileComplete'] = new JsExpression('function (evt, uiEvt) {
    if (uiEvt.result.error) {
        alert(uiEvt.result.error);
    } else {
        jQuery(this).find("input[type=\'hidden\']").val(uiEvt.result.file);
    }
}');

$this->registerJs('(function($) {
    var $container = $("#' . $id . '");

    $container.on("click", "[data-fileapi=\'delete\']", function(event) {
        event.preventDefault();
        $container.fileapi("clear");
    });

    $("[data-fileapi=\"empty.show\"]").show();
})(jQuery);');