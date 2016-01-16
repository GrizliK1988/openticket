<?php

declare(strict_types=1);

namespace DG\OpenticketBundle\Entity;


/**
 * Class TranslationFilter
 *
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class TranslationFilter extends AbstractFilter
{
    /**
     * @var \string
     */
    protected $locale;

    /**
     * @var \string
     */
    protected $domain;

    /**
     * TranslationFilter constructor.
     * @param string $locale
     * @param string $domain
     */
    public function __construct(\string $locale = null, \string $domain = null)
    {
        $this->locale = $locale;
        $this->domain = $domain;
    }

    /**
     * @return string
     */
    public function getLocale(): \string
    {
        return $this->locale;
    }

    /**
     * @param string $locale
     * @return TranslationFilter
     */
    public function setLocale(\string $locale): TranslationFilter
    {
        $this->locale = $locale;
        return $this;
    }

    /**
     * @return string
     */
    public function getDomain(): \string
    {
        return $this->domain;
    }

    /**
     * @param string $domain
     * @return TranslationFilter
     */
    public function setDomain(\string $domain): TranslationFilter
    {
        $this->domain = $domain;
        return $this;
    }
}