<?php

declare(strict_types=1);

namespace DG\OpenticketBundle\DataFixtures;

/**
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
interface FixtureDataInterface
{
    /**
     * Returns fixture data
     *
     * @return array
     */
    public function getData(): array;
}