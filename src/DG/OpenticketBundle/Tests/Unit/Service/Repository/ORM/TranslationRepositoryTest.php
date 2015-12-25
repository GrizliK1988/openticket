<?php

declare(strict_types=1);

namespace DG\OpenticketBundle\Tests\Unit\Service\Repository\ORM;


use DG\OpenticketBundle\Entity\TranslationFilter;
use DG\OpenticketBundle\Model\Translation;
use DG\OpenticketBundle\Services\Repository\ORM\TranslationRepository;

/**
 * Class TranslationRepositoryTest
 *
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class TranslationRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $entityManagerMock;

    /**
     * @var TranslationRepository
     */
    private $repository;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->entityManagerMock = $this->getMockForAbstractClass('Doctrine\ORM\EntityManagerInterface');
        $this->repository = new TranslationRepository($this->entityManagerMock);
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        $this->entityManagerMock = null;
        $this->repository = null;
    }

    public function testLoadById()
    {
        $translation = Translation::create()->setDomain('domain')->setKey(100);
        $this->entityManagerMock->expects($this->once())->method('getRepository')
            ->with('DGOpenticketBundle:Translation')->willReturn(
                new class ($translation) {
                    private $translation;

                    public function __construct(Translation $translation)
                    {
                        $this->translation = $translation;
                    }

                    public function find(\int $id)
                    {
                        return $this->translation->setKey($id);
                    }
                });

        $foundTranslation = $this->repository->loadById(100);
        $this->assertEquals($translation, $foundTranslation);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testLoadByNotExistedId()
    {
        $this->entityManagerMock->expects($this->once())->method('getRepository')
            ->with('DGOpenticketBundle:Translation')->willReturn(
                new class () {
                    public function find()
                    {
                        return null;
                    }
                });

        $this->repository->loadById(100);
    }

    public function testLoadAll()
    {
        $this->entityManagerMock->expects($this->once())->method('getRepository')
            ->with('DGOpenticketBundle:Translation')->willReturn(
                new class () {
                    public function findAll()
                    {
                        return [Translation::create(), Translation::create()];
                    }
                });

        $foundTranslations = $this->repository->loadAll();
        $this->assertEquals([Translation::create(), Translation::create()], $foundTranslations);
    }

    public function testLoadByFilter()
    {
        /** @var \Doctrine\Common\Persistence\ObjectRepository|\PHPUnit_Framework_MockObject_MockObject $repositoryMock */
        $repositoryMock = $this->getMockForAbstractClass('Doctrine\Common\Persistence\ObjectRepository');
        $this->entityManagerMock->expects($this->once())->method('getRepository')->willReturn($repositoryMock);

        $repositoryMock->expects($this->once())->method('findBy')->with(['domain' => 'messages'])->willReturn([]);

        $filter = (new TranslationFilter())->setDomain('messages');
        $foundTranslations = $this->repository->loadByFilter($filter);

        $this->assertSame([], $foundTranslations);
    }
}
