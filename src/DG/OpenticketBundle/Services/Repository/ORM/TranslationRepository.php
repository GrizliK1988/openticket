<?php

declare(strict_types=1);

namespace DG\OpenticketBundle\Services\Repository\ORM;


use DG\OpenticketBundle\Entity\TranslationFilter;
use DG\OpenticketBundle\Model\Translation;
use DG\OpenticketBundle\Services\Repository\TranslationRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class TranslationRepository
 *
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class TranslationRepository implements TranslationRepositoryInterface
{
    const DIC_NAME = 'dg_openticket.repository.orm.translations';

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * TranslationRepository constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Loads object by id
     *
     * @param int $id
     * @return Translation
     *
     * @throws \InvalidArgumentException
     */
    public function loadById(\int $id): Translation
    {
        $repository = $this->entityManager->getRepository('DGOpenticketBundle:Translation');
        $translation = $repository->find($id);

        if ($translation === null) {
            throw new \InvalidArgumentException(sprintf('Translation with id=%s does not exist', (string)$id));
        }

        return $translation;
    }

    /**
     * Loads all translations
     *
     * @return Translation[]
     */
    public function loadAll(): array
    {
        $repository = $this->entityManager->getRepository('DGOpenticketBundle:Translation');

        return $repository->findAll();
    }

    /**
     * Loads translations by given filter
     *
     * @param TranslationFilter $filter
     * @return \DG\OpenticketBundle\Model\Translation[]
     */
    public function loadByFilter(TranslationFilter $filter): array
    {
        $repository = $this->entityManager->getRepository('DGOpenticketBundle:Translation');
        return $repository->findBy($filter->toArray());
    }
}