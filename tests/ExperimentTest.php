<?php

declare(strict_types=1);

namespace Xepozz\AB\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Xepozz\AB\Experiment\InMemoryExperimentStorage;
use Xepozz\AB\Tests\Support\IntEnum;
use Xepozz\AB\Tests\Support\StringEnum;

class ExperimentTest extends TestCase
{
    #[DataProvider('dataIsActive')]
    public function testInGroup(
        array $experiments,
        string|int $identity,
        string|int|\BackedEnum $experiment,
        bool $expectedResult
    ): void {
        $storage = new InMemoryExperimentStorage($experiments);

        $actualResult = $storage->isInExperiment($identity, $experiment);
        $this->assertEquals($expectedResult, $actualResult);
    }

    public static function dataIsActive(): iterable
    {
        yield 'empty' => [
            'experiments' =>[],
            'identity' => 'user1',
            'experiment' => 'exp_1',
            'expectedResult' => false,
        ];
        yield 'in exp' => [
            'experiments' => ['exp_1' => ['user_1', 'user_2']],
            'identity' => 'user_1',
            'experiment' => 'exp_1',
            'expectedResult' => true,
        ];
        yield 'not in exp' => [
            'experiments' => ['exp_1' => ['user_2']],
            'identity' => 'user_1',
            'experiment' => 'exp_1',
            'expectedResult' => false,
        ];
        yield 'int exp' => [
            'experiments' => [123 => [11, 22]],
            'identity' => 11,
            'experiment' => 123,
            'expectedResult' => true,
        ];
        yield 'in exp, exp as enum' => [
            'experiments' => [StringEnum::CASE1->value => ['user1', 123]],
            'identity' => 'user1',
            'experiment' => StringEnum::CASE1,
            'expectedResult' => true,
        ];
        yield 'not in exp, exp as enum' => [
            'experiments' => [IntEnum::CASE1->value => ['user2', 444]],
            'identity' => 'user1',
            'experiment' => IntEnum::CASE2,
            'expectedResult' => false,
        ];
        yield 'in mixed types' => [
            'experiments' => [
                StringEnum::CASE2->value => [
                    123,
                    'user1',
                    StringEnum::CASE1,
                ],
            ],
            'identity' => 'user1',
            'experiment' => StringEnum::CASE2,
            'expectedResult' => true,
        ];
    }
}