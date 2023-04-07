<?php

declare(strict_types=1);

namespace Xepozz\AB\Experiment;

use BackedEnum;
use InvalidArgumentException;

final class InMemoryExperimentStorage implements ExperimentStorageInterface
{
    public function __construct(private array $experiments = [])
    {
        foreach ($this->experiments as $experiment => $identities) {
            if (!is_string($experiment) && !is_integer($experiment)) {
                throw new InvalidArgumentException(
                    sprintf(
                        'Experiment "%s" reference should be either string or integer type, "%s" given instead.',
                        $experiment,
                        is_scalar($experiment) ? $experiment : get_debug_type($experiment),
                    )
                );
            }
        }
    }

    public function isInExperiment(int|string $identity, BackedEnum|int|string $experiment): bool
    {
        if ($experiment instanceof BackedEnum) {
            $experiment = $experiment->value;
        }

        if (!isset($this->experiments[$experiment])) {
            return false;
        }

        return in_array($identity, $this->experiments[$experiment], true);
    }

    public function getAllExperimentsForIdentity(int|string $identity): array
    {
        $result = [];
        foreach ($this->experiments as $experiment => $identities) {
            if ($identity === $experiment) {
                $result[] = $experiment;
            }
        }
        return $result;
    }

    public function addToExperiment(int|string $identity, BackedEnum|int|string $experiment): void
    {
        if ($this->isInExperiment($identity, $experiment)) {
            return;
        }
        if ($experiment instanceof BackedEnum) {
            $experiment = $experiment->value;
        }
        $this->experiments[$experiment][] = $identity;
    }

    public function deleteFromExperiment(int|string $identity, BackedEnum|int|string $experiment): void
    {
        if (!$this->isInExperiment($identity, $experiment)) {
            return;
        }
        $position = array_search($identity, $this->experiments[$experiment]);
        unset($this->experiments[$experiment][$position]);
    }
}
