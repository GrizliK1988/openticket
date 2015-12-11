<?php

declare(strict_types=1);

namespace DG\OpenticketBundle\Event\Fixture;


use Symfony\Component\EventDispatcher\Event;

/**
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
class RecordLoadEvent extends Event
{
    const NAME = 'dg_openticket_bundle.db_fixture.record_load';
}