<?php

declare(strict_types=1);

namespace DG\OpenticketBundle\Entity;


/**
 * Class NormalizableFilterInterface
 *
 * @author Dmitry Grachikov <dgrachikov@gmail.com>
 */
interface NormalizableFilterInterface
{
    public function toArray(): array;
}