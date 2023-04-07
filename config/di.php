<?php

declare(strict_types=1);

use Xepozz\AB\Experiment\ExperimentStorageInterface;

return [
    ExperimentStorageInterface::class => [
        'class' => \Xepozz\AB\Experiment\InMemoryExperimentStorage::class,
        '__construct()' => [
            'experiments' => [],
        ],
    ],
];