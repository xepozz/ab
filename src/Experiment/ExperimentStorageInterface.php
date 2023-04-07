<?php

declare(strict_types=1);

namespace Xepozz\AB\Experiment;

use BackedEnum;

interface ExperimentStorageInterface
{
    public function isInExperiment(
        string|int $identity,
        string|int|BackedEnum $experiment
    ): bool;

    public function getAllExperimentsForIdentity(int|string $identity): array;

    public function addToExperiment(
        string|int $identity,
        string|int|BackedEnum $experiment
    ): void;

    public function deleteFromExperiment(
        string|int $identity,
        string|int|BackedEnum $experiment
    ): void;
}