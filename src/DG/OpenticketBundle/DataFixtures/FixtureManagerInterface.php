<?php

namespace DG\OpenticketBundle\DataFixtures;


/**
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
interface FixtureManagerInterface
{
    const DIC_NAME = 'dg_openticket.db_fixture.manager';

    /**
     * @param FixtureLoaderInterface $fixtureLoader
     * @return void
     */
    public function addFixtureLoader(FixtureLoaderInterface $fixtureLoader);

    /**
     * @return FixtureLoaderInterface[]
     */
    public function getFixtureLoaders(): array;
}