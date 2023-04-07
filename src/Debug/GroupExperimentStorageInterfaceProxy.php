<?php

declare(strict_types=1);

namespace Xepozz\AB\Debug;

use BackedEnum;
use Xepozz\AB\Experiment\ExperimentStorageInterface;
use Xepozz\AB\GroupExperiment\GroupExperimentStorageInterface;

final class GroupExperimentStorageInterfaceProxy implements GroupExperimentStorageInterface
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

    public function isInGroup(
        int|string $identity,
        BackedEnum|int|string $experiment,
        BackedEnum|int|string $group,
    ): bool {
        return $this->decorated->{__FUNCTION__}(...func_get_args());
    }

    public function getGroup(int|string $identity, BackedEnum|int|string $experiment,): int|string
    {
        return $this->decorated->{__FUNCTION__}(...func_get_args());
    }

    public function addToGroup(
        int|string $identity,
        BackedEnum|int|string $experiment,
        BackedEnum|int|string $group,
    ): void {
        $this->collector->collect(
            'experiments',
            __FUNCTION__,
            [
                'identity' => $identity,
                'experiment' => $experiment,
                'group' => $group,
            ],
        );
        $this->decorated->{__FUNCTION__}(...func_get_args());
    }

    public function removeFromGroup(
        int|string $identity,
        BackedEnum|int|string $experiment,
        BackedEnum|int|string $group,
    ): void {
        $this->collector->collect(
            'experiments',
            __FUNCTION__,
            [
                'identity' => $identity,
                'experiment' => $experiment,
                'group' => $group,
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
