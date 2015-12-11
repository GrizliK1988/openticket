<?php
/**
 * User: Dmitry Grachikov
 * Date: 06.12.15
 * Time: 17:49
 */

namespace DG\OpenticketBundle\Tests\Unit\DataFixtures\ORM;


use DG\OpenticketBundle\DataFixtures\FixtureData;

class FixtureDataTest extends \PHPUnit_Framework_TestCase
{
    public function testRegisterGetData()
    {
        $fixtureData = new FixtureData(['dummy_data']);

        $this->assertSame(['dummy_data'], $fixtureData->getData());
    }

    /**
     * @expectedException \TypeError
     */
    public function testRegisterWrongData()
    {
        new FixtureData('not array');
    }
}
