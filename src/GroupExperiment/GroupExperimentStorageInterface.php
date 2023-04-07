<?php

declare(strict_types=1);

namespace Xepozz\AB\GroupExperiment;

use BackedEnum;
use Xepozz\AB\Experiment\ExperimentStorageInterface;

interface GroupExperimentStorageInterface extends ExperimentStorageInterface
{
    public function isInGroup(
        string|int $identity,
        string|int|BackedEnum $experiment,
        string|int|BackedEnum $group,
    ): bool;

    public function getGroup(
        string|int $identity,
        string|int|BackedEnum $experiment,
    ): int|string;

    public function addToGroup(
        string|int $identity,
        string|int|BackedEnum $experiment,
        string|int|BackedEnum $group,
    ): void;

    public function removeFromGroup(
        string|int $identity,
        string|int|BackedEnum $experiment,
        string|int|BackedEnum $group,
    ): void;
}