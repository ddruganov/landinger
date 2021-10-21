<?php

namespace service\controllers\actions\multimedia;

use api\controllers\actions\ApiAction;
use core\components\ExecutionResult;
use core\components\helpers\DateHelper;
use core\components\helpers\UserHelper;
use core\models\service\Image;
use Exception;
use Throwable;
use Yii;

class ActionUploadImage extends ApiAction
{
    public const ALLOWED_MIMETYPES = [
        'image/jpeg',
        'image/jpg',
        'image/png',
    ];

    public const ERROR_MIMETYPE = 0x100;
    public const ERROR_TOO_BIG = 0x110;
    public const ERROR_FOLDER = 0x120;
    public const ERROR_SAVE_TO_DISK = 0x130;
    public const ERROR_SAVE_TO_DATABASE = 0x140;

    public function run()
    {
        $image = $_FILES['image'] ?? null;
        if (!$image) {
            return $this->apiResponse(new ExecutionResult(false, ['exception' => 'Отсутствует изображение']));
        }

        $name = $image['name'];
        $extension = @end(explode('.', $name));
        $type = $image['type'];
        $filepath = $image['tmp_name'];
        $size = $image['size'];
        $creationDate = DateHelper::now('Y/m/d');
        $hash = md5_file($filepath);
        $outputFolder = Yii::getAlias('@imageUpload') . '/' . $creationDate;
        $fullPath = $outputFolder . '/' . $hash . '.' . $extension;

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!in_array($type, self::ALLOWED_MIMETYPES)) {
                throw new Exception('', self::ERROR_MIMETYPE);
            }

            if ($size > Yii::$app->params['multimedia']['image']['upload']['maxFileSize']) {
                throw new Exception('', self::ERROR_TOO_BIG);
            }

            if (!file_exists($outputFolder) && !mkdir($outputFolder, 0777, true)) {
                throw new Exception('', self::ERROR_SAVE_TO_DISK);
            }

            if (file_put_contents($fullPath, file_get_contents($filepath)) === false) {
                throw new Exception('', self::ERROR_SAVE_TO_DATABASE);
            }

            $image = new Image([
                'creationDate' => $creationDate,
                'creatorId' => UserHelper::id(),
                'size' => $size,
                'hash' => $hash,
                'extension' => $extension
            ]);
            if (!$image->save()) {
                throw new Exception('', self::ERROR_SAVE_TO_DATABASE);
            }

            $transaction->commit();
            return $this->apiResponse(new ExecutionResult(true, [], $image->getData()));
        } catch (Throwable $t) {
            $transaction->rollBack();
            unlink($fullPath);

            $message = 'Неизвестная ошибка';
            switch ($t->getCode()) {
                case self::ERROR_MIMETYPE:
                    $message = 'Неизвестный тип изображения';
                    break;
                case self::ERROR_TOO_BIG:
                    $message = 'Изображение слишком большое';
                    break;
                case self::ERROR_FOLDER:
                    $message = 'Ошибка создания папки для изображения';
                    break;
                case self::ERROR_SAVE_TO_DISK:
                    $message = 'Ошибка сохранения изображения на диск';
                    break;
                case self::ERROR_SAVE_TO_DATABASE:
                    $message = 'Ошибка сохранения изображения в базу данных';
                    break;
            }

            return $this->apiResponse(new ExecutionResult(false, ['exception' => $message]));
        }
    }
}
