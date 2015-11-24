<?php

namespace DG\OpenticketBundle\DataFixtures;


/**
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class FixturesManager implements FixturesManagerInterface
{
    /**
     * @var FixtureInterface[]
     */
    private $fixtures = [];

    /**
     * Returns list of fixtures
     *
     * @return FixtureInterface[]
     */
    public function getFixtures()
    {
        return $this->fixtures;
    }

    /**
     * Stores fixture in a service to be used later
     *
     * @param FixtureInterface $fixture
     * @return void
     */
    public function addFixture(FixtureInterface $fixture)
    {
        $this->fixtures[] = $fixture;
    }
}