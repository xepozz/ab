<?php

declare(strict_types=1);

use Xepozz\AB\Debug\ExperimentStorageInterfaceProxy;
use Xepozz\AB\Debug\ExperimentCollector;
use Xepozz\AB\Experiment\ExperimentStorageInterface;

return [
    'yiisoft/yii-debug' => [
        'collectors' => [
            ExperimentCollector::class,
        ],
        'trackedServices' => [
            ExperimentStorageInterface::class => [ExperimentStorageInterfaceProxy::class, ExperimentCollector::class],
        ],
    ],
];