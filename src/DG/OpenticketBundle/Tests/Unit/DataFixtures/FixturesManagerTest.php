<?php

namespace DG\OpenticketBundle\Tests\Unit\DataFixtures;


use DG\OpenticketBundle\DataFixtures\FixtureInterface;
use DG\OpenticketBundle\DataFixtures\FixturesManager;

/**
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class FixturesManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testAddGetFixture()
    {
        /** @var FixtureInterface $fixtureMock */
        $fixtureMock = $this->getMockForAbstractClass('DG\OpenticketBundle\DataFixtures\FixtureInterface');

        $manager = new FixturesManager();
        $this->assertEquals(0, count($manager->getFixtures()));

        $manager->addFixture($fixtureMock);
        $this->assertEquals(1, count($manager->getFixtures()));
    }
}
