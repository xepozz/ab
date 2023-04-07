<?php

declare(strict_types=1);

use Xepozz\AB\Debug\ExperimentStorageInterfaceProxy;
use Yiisoft\Yii\Http\Event\AfterRequest;
use Yiisoft\Yii\Http\Event\BeforeRequest;

return [
    BeforeRequest::class => [
        [ExperimentStorageInterfaceProxy::class, 'collectAll'],
    ],
    AfterRequest::class => [
        [ExperimentStorageInterfaceProxy::class, 'collectAll'],
    ],
];