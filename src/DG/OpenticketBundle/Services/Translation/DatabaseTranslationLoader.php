<?php

declare(strict_types=1);

namespace DG\OpenticketBundle\Services\Translation;


use DG\OpenticketBundle\Entity\TranslationFilter;
use DG\OpenticketBundle\Services\Repository\TranslationRepositoryInterface;
use Symfony\Component\Translation\Exception\InvalidResourceException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use Symfony\Component\Translation\Loader\LoaderInterface;
use Symfony\Component\Translation\MessageCatalogue;

/**
 * Class DatabaseLoader
 *
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class DatabaseTranslationLoader implements LoaderInterface
{
    const DIC_NAME = 'dg_openticket.translations.db_loader';

    const DOMAIN_TICKET_STATUS = 'ticket_status';

    const DOMAIN_TICKET_CATEGORY = 'ticket_category';

    const DOMAINS = [self::DOMAIN_TICKET_STATUS, self::DOMAIN_TICKET_CATEGORY];

    /**
     * @var TranslationRepositoryInterface
     */
    private $translationRepository;

    /**
     * DatabaseTranslationLoader constructor.
     * @param TranslationRepositoryInterface $translationRepository
     */
    public function __construct(TranslationRepositoryInterface $translationRepository)
    {
        $this->translationRepository = $translationRepository;
    }

    /**
     * Loads a locale.
     *
     * @param mixed $resource A resource
     * @param string $locale A locale
     * @param string $domain The domain
     *
     * @return MessageCatalogue A MessageCatalogue instance
     *
     * @throws NotFoundResourceException when the resource cannot be found
     * @throws InvalidResourceException  when the resource cannot be loaded
     */
    public function load($resource, $locale, $domain = 'messages'): MessageCatalogue
    {
        try {
            $translations = $this->translationRepository->loadByFilter(new TranslationFilter($locale, $domain));
        } catch (\Exception $dbException) {
            throw new InvalidResourceException('Translations cannot be loaded', 0, $dbException);
        }

        $messages = [$domain => []];
        foreach ($translations as $translation) {
            $messages[$domain][$translation->getKey()] = $translation->getTranslation();
        }

        return new MessageCatalogue($locale, $messages);
    }
}