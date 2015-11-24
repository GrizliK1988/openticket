<?php

namespace DG\OpenticketBundle\DataFixtures;


/**
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
interface FixturesManagerInterface
{
    const DIC_NAME = 'dg_openticket.db_fixtures.manager';

    /**
     * Returns list of fixtures
     *
     * @return FixtureInterface[]
     */
    public function getFixtures();

    /**
     * Stores fixture in a service to be used later
     *
     * @param FixtureInterface $fixture
     * @return void
     */
    public function addFixture(FixtureInterface $fixture);
}