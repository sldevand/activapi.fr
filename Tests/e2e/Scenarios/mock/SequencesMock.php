<?php

namespace Tests\e2e\Scenarios\mock;

use Entity\Scenario\Action;
use Entity\Scenario\Sequence;
use Tests\Model\Actionneurs\mock\ActionneursMock;

/**
 * Class SequencesMock
 * @package Tests\e2e\Scenarios\mock
 */
class SequencesMock
{
    /**
     * @return \Entity\Scenario\Sequence[]
     */
    public static function getSequences(): array
    {
        return [
            new Sequence(
                [
                    'nom' => 'SequenceTest1',
                    'actions' => []
                ]
            ),
            new Sequence(
                [
                    'nom' => 'SequenceTest2',
                    'actions' => []
                ]
            )
        ];
    }
}