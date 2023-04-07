<?php

declare(strict_types=1);

namespace Xepozz\AB\GroupExperiment;

use BackedEnum;
use InvalidArgumentException;

final class InMemoryGroupExperimentStorageStorage implements GroupExperimentStorageInterface
{
    public function __construct(private array $experiments = [])
    {
        foreach ($this->experiments as $experiment => $groups) {
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
        $position = array_search($identity, $this->experiments[$identity]);
        unset($this->experiments[$experiment][$position]);
    }

    public function isInGroup(
        int|string $identity,
        BackedEnum|int|string $experiment,
        BackedEnum|int|string $group,
    ): bool {
        if (!$this->isInExperiment($identity, $experiment)) {
            return false;
        }
        if ($group instanceof BackedEnum) {
            $group = $group->value;
        }
        if (!isset($this->experiments[$experiment][$group])) {
            return false;
        }

        return in_array($identity, $this->experiments[$experiment][$group], true);
    }

    public function getGroup(int|string $identity, BackedEnum|int|string $experiment): int|string
    {
        if (!$this->isInExperiment($identity, $experiment)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Identity "%s" does not take part in the experiment "%s".',
                    $identity,
                    $experiment instanceof BackedEnum ? $experiment->value : $experiment,
                )
            );
        }

        foreach ($this->experiments[$experiment] as $group => $identities) {
            if (in_array($identity, $identities, true)) {
                return $group;
            }
        }

        throw new InvalidArgumentException(
            sprintf(
                'Identity "%s" does not take part in any group the experiment "%s".',
                $identity,
                $experiment instanceof BackedEnum ? $experiment->value : $experiment,
            )
        );
    }

    public function addToGroup(
        int|string $identity,
        BackedEnum|int|string $experiment,
        BackedEnum|int|string $group,
    ): void {
        if ($this->isInGroup($identity, $experiment, $group)) {
            return;
        }

        $this->experiments[$experiment][$group][] = $identity;
    }

    public function removeFromGroup(
        int|string $identity,
        BackedEnum|int|string $experiment,
        BackedEnum|int|string $group,
    ): void {
        if (!$this->isInGroup($identity, $experiment, $group)) {
            return;
        }
        $position = array_search($identity, $this->experiments[$identity][$group]);
        unset($this->experiments[$experiment][$group][$position]);
    }
}