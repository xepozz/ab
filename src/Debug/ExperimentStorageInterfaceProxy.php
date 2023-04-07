<?php

declare(strict_types=1);

namespace Xepozz\AB\Debug;

use BackedEnum;
use Xepozz\AB\Experiment\ExperimentStorageInterface;

final class ExperimentStorageInterfaceProxy implements ExperimentStorageInterface
{
    public function __construct(
        private ExperimentStorageInterface $decorated,
        private ExperimentCollector $collector,
    ) {
    }

    public function isInExperiment(int|string $identity, BackedEnum|int|string $experiment): bool
    {
        $this->collector->collect(
            'experiments',
            __FUNCTION__,
            [
                'identity' => $identity,
                'experiment' => $experiment,
            ],
        );
        return $this->decorated->{__FUNCTION__}(...func_get_args());
    }

    public function getAllExperimentsForIdentity(int|string $identity): array
    {
        return $this->decorated->{__FUNCTION__}(...func_get_args());
    }

    public function addToExperiment(int|string $identity, BackedEnum|int|string $experiment): void
    {
        $this->collector->collect(
            'experiments',
            __FUNCTION__,
            [
                'identity' => $identity,
                'experiment' => $experiment,
            ],
        );
        $this->decorated->{__FUNCTION__}(...func_get_args());
    }

    public function deleteFromExperiment(int|string $identity, BackedEnum|int|string $experiment): void
    {
        $this->collector->collect(
            'experiments',
            __FUNCTION__,
            [
                'identity' => $identity,
                'experiment' => $experiment,
            ],
        );
        $this->decorated->{__FUNCTION__}(...func_get_args());
    }

    public function collectAll(): void
    {
        $result = $this->decorated->getAllExperimentsForIdentity($identity = 1);
        $this->collector->collectStatic(
            'experiments',
            [
                'experiments' => [
                    'identity' => $identity,
                    'result' => $result,
                ],
            ],
        );
    }
}
