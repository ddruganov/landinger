<?php

Yii::setAlias('@root', dirname(__DIR__));

Yii::setAlias('@api', Yii::getAlias('@root/api'));
Yii::setAlias('@client', Yii::getAlias('@root/client'));
Yii::setAlias('@console', Yii::getAlias('@root/console'));
Yii::setAlias('@core', Yii::getAlias('@root/core'));

Yii::setAlias('@service', Yii::getAlias('@root/service'));
Yii::setAlias('@imageUpload', '@service/web/upload/image');

Yii::setAlias('@vendor', Yii::getAlias('@root/vendor'));
Yii::setAlias('@bower', Yii::getAlias('@vendor/bower-asset'));
Yii::setAlias('@npm', Yii::getAlias('@vendor/npm-asset'));

define('SUPERUSER_ID', 2);
