<?php

namespace otsec\yii2\fileapi;

/**
 * Interface ModelInterface
 */
interface ModelInterface
{
    /**
     * Restores the list of files uploaded earlier
     * IE < 9 — NOT SUPPORTED
     *
     * [
     *     [
     *         src: "http://path/to/filename.png",
     *         type: "image/png",
     *         name: "filename.png"
     *         size: 31409,
     *         data: [ id: 999, token: "..." ],
     *     ],
     *     ...
     * ],
     *
     * @param string $attribute model attribute
     *
     * @return array
     */
    public function getFileAPIUploadedFiles($attribute);
}