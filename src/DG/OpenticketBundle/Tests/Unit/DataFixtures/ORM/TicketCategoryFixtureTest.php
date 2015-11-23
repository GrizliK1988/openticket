<?php

namespace DG\OpenticketBundle\Tests\Unit\DataFixtures\ORM;


use DG\OpenticketBundle\DataFixtures\ORM\TicketCategoryFixture;
use DG\OpenticketBundle\Model\Ticket\Category;
use DG\OpenticketBundle\Model\Translation;
use Doctrine\ORM\EntityManager;

/**
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class TicketCategoryFixtureTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var EntityManager|\PHPUnit_Framework_MockObject_MockObject
     */
    private $managerMock;

    /**
     * @var TicketCategoryFixture
     */
    private $fixture;

    protected function setUp()
    {
        $this->managerMock = $this->getMockBuilder('Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock();
        $this->fixture = new TicketCategoryFixture($this->managerMock);
    }

    protected function tearDown()
    {
        $this->managerMock = null;
        $this->fixture = null;
    }

    public function testLoad()
    {
        $this->managerMock->expects($this->at(0))->method('persist')->with($this->callback(function (Category $category) {
            $this->assertFalse($category->isDeleted());
            return true;
        }))->willReturnCallback(function (Category $category) {
            $this->setCategoryId($category, 1);
        });
        $this->managerMock->expects($this->at(1))->method('persist')->with($this->callback(function (Category $category) {
            $this->assertFalse($category->isDeleted());
            return true;
        }))->willReturnCallback(function (Category $category) {
            $this->setCategoryId($category, 2);
        });
        $this->managerMock->expects($this->at(2))->method('persist')->with($this->callback(function (Category $category) {
            $this->assertFalse($category->isDeleted());
            return true;
        }))->willReturnCallback(function (Category $category) {
            $this->setCategoryId($category, 3);
        });

        $this->managerMock->expects($this->at(3))->method('flush');

        $this->managerMock->expects($this->at(4))->method('persist')->with($this->callback(function (Translation $translation) {
            $this->assertEquals('ticket_category', $translation->getDomain());
            $this->assertEquals('en', $translation->getLocale());
            $this->assertEquals(1, $translation->getKey());
            $this->assertEquals('Bug', $translation->getTranslation());
            return true;
        }));
        $this->managerMock->expects($this->at(5))->method('persist')->with($this->callback(function (Translation $translation) {
            $this->assertEquals('ticket_category', $translation->getDomain());
            $this->assertEquals('ru', $translation->getLocale());
            $this->assertEquals(1, $translation->getKey());
            $this->assertEquals('Ошибка', $translation->getTranslation());
            return true;
        }));

        $this->managerMock->expects($this->at(6))->method('persist')->with($this->callback(function (Translation $translation) {
            $this->assertEquals('ticket_category', $translation->getDomain());
            $this->assertEquals('en', $translation->getLocale());
            $this->assertEquals(2, $translation->getKey());
            $this->assertEquals('Improvement', $translation->getTranslation());
            return true;
        }));
        $this->managerMock->expects($this->at(7))->method('persist')->with($this->callback(function (Translation $translation) {
            $this->assertEquals('ticket_category', $translation->getDomain());
            $this->assertEquals('ru', $translation->getLocale());
            $this->assertEquals(2, $translation->getKey());
            $this->assertEquals('Улучшение', $translation->getTranslation());
            return true;
        }));

        $this->managerMock->expects($this->at(8))->method('persist')->with($this->callback(function (Translation $translation) {
            $this->assertEquals('ticket_category', $translation->getDomain());
            $this->assertEquals('en', $translation->getLocale());
            $this->assertEquals(3, $translation->getKey());
            $this->assertEquals('Question', $translation->getTranslation());
            return true;
        }));
        $this->managerMock->expects($this->at(9))->method('persist')->with($this->callback(function (Translation $translation) {
            $this->assertEquals('ticket_category', $translation->getDomain());
            $this->assertEquals('ru', $translation->getLocale());
            $this->assertEquals(3, $translation->getKey());
            $this->assertEquals('Вопрос', $translation->getTranslation());
            return true;
        }));

        $this->managerMock->expects($this->at(10))->method('flush');

        $this->fixture->load();
    }

    private function setCategoryId(Category $category, $id)
    {
        $categoryReflected = new \ReflectionObject($category);
        $idProperty = $categoryReflected->getProperty('id');
        $idProperty->setAccessible(true);
        $idProperty->setValue($category, $id);
    }
}
