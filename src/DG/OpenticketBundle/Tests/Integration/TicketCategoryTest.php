<?php

namespace DG\OpenticketBundle\Tests\Integration;
use DG\OpenticketBundle\Model\Ticket;


/**
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class TicketCategoryTest extends AbstractORMTest
{
    public function testCategoryCreateReadUpdate()
    {
        $category = Ticket\Category::create()
            ->setId(1)
            ->setLocale('en')
            ->setName('Bug');

        $this->persist($category);
        $this->manager->flush();
        $this->manager->clear();

        $categoryRepo = $this->manager->getRepository('DGOpenticketBundle:Ticket\Category');

        /** @var Ticket\Category[] $foundCategoriesByLocale */
        $foundCategoriesByLocale = $categoryRepo->findBy(['locale' => 'en']);
        $this->assertEquals(1, count($foundCategoriesByLocale));
        $this->assertEquals(1, $foundCategoriesByLocale[0]->getId());
        $this->assertEquals('en', $foundCategoriesByLocale[0]->getLocale());
        $this->assertEquals('Bug', $foundCategoriesByLocale[0]->getName());

        /** @var Ticket\Category $foundCategoryByPK */
        $foundCategoryByPK = $categoryRepo->find(['id' => 1, 'locale' => 'en']);
        $this->assertNotNull($foundCategoryByPK);

        $foundCategoriesByName = $categoryRepo->findBy(['name' => 'Bug']);
        $this->assertEquals(1, count($foundCategoriesByName));

        $foundCategoryByPK->setName('Improvement');
        $foundCategoryByPK->setDeleted(true);
        $this->persist($foundCategoryByPK);
        $this->manager->flush();
        $this->manager->clear();

        /** @var Ticket\Category $foundUpdatedCategoryByPK */
        $foundUpdatedCategoryByPK = $categoryRepo->find(['id' => 1, 'locale' => 'en']);
        $this->assertEquals('Improvement', $foundUpdatedCategoryByPK->getName());
        $this->assertTrue($foundUpdatedCategoryByPK->isDeleted());

        $foundUpdatedCategoryByPK->setLocale(str_pad('', 20, 'A')); //too long locale
        try {
            $this->persist($foundUpdatedCategoryByPK);
            $this->manager->flush();
            $this->fail();
        } catch (\Exception $exception) {
            $this->assertInstanceOf('Doctrine\DBAL\Exception\DriverException', $exception);
        }
    }
}
 