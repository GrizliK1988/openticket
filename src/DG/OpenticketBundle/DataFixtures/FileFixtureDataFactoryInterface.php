<?php

declare(strict_types=1);

namespace DG\OpenticketBundle\DataFixtures;


/**
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
interface FileFixtureDataFactoryInterface
{
    /**
     * @param string $fixtureFilePath
     * @return FixtureDataInterface
     * @throws \InvalidArgumentException
     */
    public function createFixtureData(\string $fixtureFilePath): FixtureDataInterface;
}