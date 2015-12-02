<?php

namespace DG\OpenticketBundle\Tests\Unit\DataFixtures;


use DG\OpenticketBundle\DataFixtures\FixtureLoaderInterface;
use DG\OpenticketBundle\DataFixtures\FixtureManager;

/**
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class FixtureManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testAddGetFixture()
    {
        /** @var FixtureLoaderInterface $fixtureMock */
        $fixtureMock = $this->getMockForAbstractClass('DG\OpenticketBundle\DataFixtures\FixtureLoaderInterface');

        $manager = new FixtureManager();
        $this->assertEquals(0, count($manager->getFixtureLoaders()));

        $manager->addFixtureLoader($fixtureMock);
        $this->assertEquals(1, count($manager->getFixtureLoaders()));
    }
}
