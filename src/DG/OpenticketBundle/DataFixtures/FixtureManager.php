<?php

declare(strict_types=1);

namespace DG\OpenticketBundle\DataFixtures;


/**
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class FixtureManager implements FixtureManagerInterface
{
    /**
     * @var FixtureLoaderInterface[]
     */
    private $fixtureLoaders = [];

    /**
     * @param FixtureLoaderInterface $fixtureLoader
     * @return void
     */
    public function addFixtureLoader(FixtureLoaderInterface $fixtureLoader)
    {
        $this->fixtureLoaders[] = $fixtureLoader;
    }

    /**
     * @return FixtureLoaderInterface[]
     */
    public function getFixtureLoaders(): array
    {
        return $this->fixtureLoaders;
    }
}