<?php

declare(strict_types=1);

namespace DG\OpenticketBundle\Event\Fixture;


use Symfony\Component\EventDispatcher\Event;

/**
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class BeforeLoadEvent extends Event
{
    const NAME = 'dg_openticket_bundle.db_fixture.before_load';

    /**
     * @var int
     */
    private $loadLength;

    /**
     * BeforeLoadEvent constructor.
     * @param int $loadLength
     */
    public function __construct(\int $loadLength)
    {
        $this->loadLength = $loadLength;
    }

    /**
     * @return int
     */
    public function getLoadLength(): \int
    {
        return $this->loadLength;
    }
}