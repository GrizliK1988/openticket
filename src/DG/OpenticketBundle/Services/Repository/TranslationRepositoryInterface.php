<?php

declare(strict_types=1);

namespace DG\OpenticketBundle\Services\Repository;


use DG\OpenticketBundle\Entity\TranslationFilter;
use DG\OpenticketBundle\Model\Translation;

/**
 * Interface RepositoryInterface
 *
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
interface TranslationRepositoryInterface
{
    /**
     * Loads object by id
     *
     * @param int $id
     * @return Translation
     *
     * @throws \InvalidArgumentException
     */
    public function loadById(\int $id): Translation;

    /**
     * Loads all translations
     *
     * @return Translation[]
     */
    public function loadAll(): array;

    /**
     * Loads translations by given filter
     *
     * @param TranslationFilter $filter
     * @return \DG\OpenticketBundle\Model\Translation[]
     */
    public function loadByFilter(TranslationFilter $filter): array;
}